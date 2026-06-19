import { serve } from "https://deno.land/std@0.168.0/http/server.ts";
import { createClient } from "https://esm.sh/@supabase/supabase-js@2.7.1";

serve(async (req) => {
  try {
    // ToyyibPay sends data as form URL-encoded in the POST body for Webhooks
    const body = await req.formData();
    
    const status_id = body.get('status_id'); // 1 = success, 2 = pending, 3 = fail
    const billcode = body.get('billcode');
    const order_id = body.get('order_id'); // This is the billExternalReferenceNo we passed

    if (!order_id) {
      throw new Error("Missing order_id");
    }

    const supabaseClient = createClient(
      Deno.env.get('SUPABASE_URL') ?? '',
      Deno.env.get('SUPABASE_SERVICE_ROLE_KEY') ?? '' // Service role to bypass RLS
    );

    let newStatus = 'pending';
    if (status_id === '1') {
      newStatus = 'completed';
    } else if (status_id === '3') {
      newStatus = 'failed';
    }

    // Update the order status securely via Edge Function
    const { error } = await supabaseClient
      .from('orders')
      .update({ status: newStatus })
      .eq('id', order_id);

    if (error) throw error;

    return new Response("Webhook received and processed", { status: 200 });
  } catch (error: any) {
    console.error("Webhook Error:", error.message);
    return new Response(error.message, { status: 400 });
  }
});
