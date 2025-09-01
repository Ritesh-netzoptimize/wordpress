<?php
/**
 * Template Name: Auth Page
 * Template Post Type: page
 */

if (isset($_POST['wp_register'])) {
    if (!isset($_POST['custom_register_nonce']) || !wp_verify_nonce($_POST['custom_register_nonce'], 'custom_register_action')) {
        die("Security check failed.");
    }

    $user_data = array(
        'user_login' => sanitize_user($_POST['reg_username']),
        'user_email' => sanitize_email($_POST['reg_email']),
        'user_pass'  => $_POST['reg_password']
    );

    $user_id = wp_insert_user($user_data);

    if (!is_wp_error($user_id)) {
        echo "<p style='color:green;'>Registration successful! You can login now.</p>";
    } else {
        echo "<p style='color:red;'>Error: " . $user_id->get_error_message() . "</p>";
    }
}

if (isset($_POST['wp_login'])) {
    if (!isset($_POST['custom_login_nonce']) || !wp_verify_nonce($_POST['custom_login_nonce'], 'custom_login_action')) {
        die("Security check failed.");
    }

    $creds = array(
        'user_login'    => sanitize_user($_POST['log']),
        'user_password' => $_POST['pwd'],
        'remember'      => true,
    );

    $user = wp_signon($creds, false);

    if (!is_wp_error($user)) {
        wp_redirect(admin_url());
        exit;
    } else {
        echo "<p style='color:red;'>Error: " . $user->get_error_message() . "</p>";
    }
}
?>

<style>
    .auth-container {
        max-width: 400px;
        margin: 40px auto;
        padding: 20px;
        background: #fff;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        border-radius: 12px;
        font-family: Arial, sans-serif;
    }
    .auth-tabs {
        display: flex;
        justify-content: space-between;
        margin-bottom: 20px;
    }
    .tab-btn {
        flex: 1;
        padding: 10px;
        border: none;
        background: #eee;
        cursor: pointer;
    }
    .tab-btn.active {
        background: #0073aa;
        color: white;
        font-weight: bold;
    }
    .auth-form {
        display: flex;
        flex-direction: column;
    }
    .auth-form input {
        margin-bottom: 12px;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 6px;
    }
    .auth-form button {
        padding: 10px;
        background: #0073aa;
        color: #fff;
        border: none;
        border-radius: 6px;
        cursor: pointer;
    }
    .auth-form button:hover {
        background: #005f8d;
    }
    .hidden {
        display: none;
    }
    .custom-layout h1 {
        text-align: center;
        margin-bottom: 20px;
    }
</style>

<?php get_header(); ?>

<main class="custom-layout">
    <h1>Welcome to My Custom Login/Register Form</h1>
    <div class="auth-container">
        <div class="auth-tabs">
            <button class="tab-btn active" onclick="showForm('login')">Login</button>
            <button class="tab-btn" onclick="showForm('register')">Register</button>
        </div>

        <form method="post" class="auth-form" id="login-form">
            <?php wp_nonce_field('custom_login_action', 'custom_login_nonce'); ?>
            <h2>Login</h2>
            <input type="text" name="log" placeholder="Username or Email" required>
            <input type="password" name="pwd" placeholder="Password" required>
            <button type="submit" name="wp_login">Login</button>
        </form>

        <form method="post" class="auth-form hidden" id="register-form">
            <?php wp_nonce_field('custom_register_action', 'custom_register_nonce'); ?>
            <h2>Register</h2>
            <input type="text" name="reg_username" placeholder="Username" required>
            <input type="email" name="reg_email" placeholder="Email" required>
            <input type="password" name="reg_password" placeholder="Password" required>
            <button type="submit" name="wp_register">Register</button>
        </form>
    </div>
</main>

<script>
    function showForm(type) {
        document.getElementById('login-form').classList.add('hidden');
        document.getElementById('register-form').classList.add('hidden');
        document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));

        if (type === 'login') {
            document.getElementById('login-form').classList.remove('hidden');
            document.querySelector('.tab-btn:nth-child(1)').classList.add('active');
        } else {
            document.getElementById('register-form').classList.remove('hidden');
            document.querySelector('.tab-btn:nth-child(2)').classList.add('active');
        }
    }
</script>

<?php get_footer(); ?>
