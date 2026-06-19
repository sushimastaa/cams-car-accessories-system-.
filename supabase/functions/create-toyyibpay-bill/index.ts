import { serve } from "https://deno.land/std@0.168.0/http/server.ts";
import { createClient } from "https://esm.sh/@supabase/supabase-js@2.7.1";

const corsHeaders = {
  'Access-Control-Allow-Origin': '*',
  'Access-Control-Allow-Headers': 'authorization, x-client-info, apikey, content-type',
};

serve(async (req) => {
  if (req.method === 'OPTIONS') {
    return new Response('ok', { headers: corsHeaders });
  }

  try {
    const authHeader = req.headers.get('Authorization')!;
    if (!authHeader) throw new Error("Missing authorization header");

    const supabaseClient = createClient(
      Deno.env.get('SUPABASE_URL') ?? '',
      Deno.env.get('SUPABASE_SERVICE_ROLE_KEY') ?? '' // Service role to bypass RLS for inserts
    );

    const supabaseUserClient = createClient(
      Deno.env.get('SUPABASE_URL') ?? '',
      Deno.env.get('SUPABASE_ANON_KEY') ?? '',
      { global: { headers: { Authorization: authHeader } } }
    );

    // Get User
    const { data: { user }, error: userError } = await supabaseUserClient.auth.getUser();
    if (userError || !user) throw new Error("Unauthorized");

    // Fetch Cart
    const { data: cartItems, error: cartError } = await supabaseUserClient
      .from('cart')
      .select('*, products(*)');
    
    if (cartError || !cartItems || cartItems.length === 0) {
      throw new Error("Cart is empty or could not be fetched");
    }

    // Calculate total amount (in RM, then multiply by 100 for ToyyibPay cents)
    let totalAmount = 0;
    cartItems.forEach((item: any) => {
      const product = Array.isArray(item.products) ? item.products[0] : item.products;
      totalAmount += parseFloat(product.price) * item.quantity;
    });

    const tax = totalAmount * 0.06;
    totalAmount = totalAmount + tax;
    
    const amountInCents = Math.round(totalAmount * 100);

    // Insert Pending Order
    const { data: order, error: orderError } = await supabaseClient
      .from('orders')
      .insert([{
        user_id: user.id,
        customer_name: user.user_metadata?.username || user.email,
        amount: totalAmount,
        status: 'pending',
        service: 'Product Purchase'
      }])
      .select()
      .single();

    if (orderError) throw orderError;

    // Call ToyyibPay
    const toyyibpaySecretKey = Deno.env.get('TOYYIBPAY_SECRET_KEY');
    const toyyibpayCategory = Deno.env.get('TOYYIBPAY_CATEGORY_CODE');

    const formData = new FormData();
    formData.append('userSecretKey', toyyibpaySecretKey!);
    formData.append('categoryCode', toyyibpayCategory!);
    formData.append('billName', `Order #${order.id.split('-')[0]}`);
    formData.append('billDescription', 'Car Accessories Purchase');
    formData.append('billPriceSetting', '1');
    formData.append('billPayorInfo', '1');
    formData.append('billAmount', amountInCents.toString());
    formData.append('billReturnUrl', `${Deno.env.get('FRONTEND_URL')}/payment_status.html?order_id=${order.id}`);
    formData.append('billCallbackUrl', `${Deno.env.get('SUPABASE_URL')}/functions/v1/toyyibpay-webhook`);
    formData.append('billExternalReferenceNo', order.id);
    formData.append('billTo', user.user_metadata?.username || 'Customer');
    formData.append('billEmail', user.email!);
    formData.append('billPhone', '0123456789');

    // Use sandbox endpoint by default
    const toyyibpayUrl = 'https://dev.toyyibpay.com/index.php/api/createBill';
    
    const response = await fetch(toyyibpayUrl, {
      method: 'POST',
      body: formData
    });

    const result = await response.json();
    if (!result || !result[0] || !result[0].BillCode) {
      throw new Error("Failed to create ToyyibPay bill");
    }

    const billCode = result[0].BillCode;

    // Update order with bill code
    await supabaseClient
      .from('orders')
      .update({ toyyibpay_bill_code: billCode })
      .eq('id', order.id);

    // Clear user cart
    await supabaseUserClient
      .from('cart')
      .delete()
      .eq('user_id', user.id);

    return new Response(
      JSON.stringify({ checkoutUrl: `https://dev.toyyibpay.com/${billCode}` }),
      { headers: { ...corsHeaders, 'Content-Type': 'application/json' } }
    );

  } catch (error: any) {
    return new Response(JSON.stringify({ error: error.message }), {
      headers: { ...corsHeaders, 'Content-Type': 'application/json' },
      status: 400,
    });
  }
});
