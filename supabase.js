// Initialize Supabase Client
// Replace with your actual Supabase URL and Anon Key
const SUPABASE_URL = 'https://bsvuodlwfvlkpeumjpqu.supabase.co';
const SUPABASE_ANON_KEY = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImJzdnVvZGx3ZnZsa3BldW1qcHF1Iiwicm9sZSI6ImFub24iLCJpYXQiOjE3ODE4ODUzMjAsImV4cCI6MjA5NzQ2MTMyMH0.KmL-0nw7oBX6bJJsB0yPjciwc25EG-z4klDdexQUETw';

// Initialize the global supabase client
window.supabase = window.supabase.createClient(SUPABASE_URL, SUPABASE_ANON_KEY);

// Helper function to check session status
async function getSession() {
    const { data, error } = await supabase.auth.getSession();
    if (error) console.error("Error getting session:", error.message);
    return data.session;
}

// Helper function to handle logout
async function handleLogout(e) {
    if (e) e.preventDefault();
    const { error } = await supabase.auth.signOut();
    if (error) {
        console.error('Logout Error:', error.message);
        alert('Error logging out.');
    } else {
        window.location.href = 'index.html';
    }
}

