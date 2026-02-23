<?php
namespace FFPS\Front;

if ( ! defined('ABSPATH') ) exit;

final class Login_Shortcode {

  const SHORTCODE = 'ffps_login';

  public function init(): void {
    add_shortcode(self::SHORTCODE, [$this, 'render']);
    add_action('init', [$this, 'handle_post']);
  }

  /**
   * Handle login POST securely
   */
  public function handle_post(): void {
    if ( ! isset($_POST['ffps_login_submit']) ) return;

    // Nonce check
    if ( ! isset($_POST['ffps_login_nonce']) || ! wp_verify_nonce($_POST['ffps_login_nonce'], 'ffps_login_action') ) {
      $this->redirect_with_error('invalid_nonce');
    }

    $redirect = isset($_POST['ffps_redirect']) ? esc_url_raw(wp_unslash($_POST['ffps_redirect'])) : home_url('/');

    $username = isset($_POST['log']) ? sanitize_user(wp_unslash($_POST['log'])) : '';
    $password = isset($_POST['pwd']) ? (string) wp_unslash($_POST['pwd']) : '';
    $remember = ! empty($_POST['rememberme']);

    if ( $username === '' || $password === '' ) {
      $this->redirect_with_error('missing_fields', $redirect);
    }

    $creds = [
      'user_login'    => $username,
      'user_password' => $password,
      'remember'      => $remember,
    ];

    $user = wp_signon($creds, is_ssl());

    if ( is_wp_error($user) ) {
      // Optional: log the error (safe)
      if ( class_exists('\FFPS\Core\Logger') ) {
        \FFPS\Core\Logger::log('Login failed', ['error' => $user->get_error_code()]);
      }
      $this->redirect_with_error('login_failed', $redirect);
    }

    // Success: redirect
    wp_safe_redirect($redirect);
    exit;
  }

  /**
   * Shortcode UI
   * Usage: [ffps_login redirect="/wp-admin/"]
   */
  public function render($atts = []): string {
    if ( is_user_logged_in() ) {
      $user = wp_get_current_user();
      $logout_url = wp_logout_url($this->current_url());

      return '<div class="ffps-login-box">'
        . '<p>You are logged in as <strong>' . esc_html($user->display_name) . '</strong>.</p>'
        . '<p><a href="' . esc_url($logout_url) . '">Log out</a></p>'
        . '</div>';
    }

    $atts = shortcode_atts([
      'redirect' => $this->current_url(),
      'title'    => 'Member Login',
    ], $atts, 'ffps_login');

    $error = isset($_GET['ffps_login_error']) ? sanitize_key($_GET['ffps_login_error']) : '';
    $error_html = $this->error_html($error);

    ob_start(); ?>
    <div class="ffps-login-box">
      <h3><?php echo esc_html($atts['title']); ?></h3>

      <?php echo $error_html; ?>

      <form method="post">
        <?php wp_nonce_field('ffps_login_action', 'ffps_login_nonce'); ?>
        <input type="hidden" name="ffps_redirect" value="<?php echo esc_attr($atts['redirect']); ?>"/>

        <p>
          <label>Username or Email</label><br/>
          <input type="text" name="log" required autocomplete="username" />
        </p>

        <p>
          <label>Password</label><br/>
          <input type="password" name="pwd" required autocomplete="current-password" />
        </p>

        <p>
          <label>
            <input type="checkbox" name="rememberme" value="1" />
            Remember me
          </label>
        </p>

        <p>
          <button type="submit" name="ffps_login_submit" value="1">Log in</button>
        </p>

        <p>
          <a href="<?php echo esc_url(wp_lostpassword_url()); ?>">Forgot password?</a>
        </p>
      </form>
    </div>
    <?php
    return (string) ob_get_clean();
  }

  private function current_url(): string {
    $scheme = is_ssl() ? 'https://' : 'http://';
    $host   = isset($_SERVER['HTTP_HOST']) ? sanitize_text_field(wp_unslash($_SERVER['HTTP_HOST'])) : '';
    $uri    = isset($_SERVER['REQUEST_URI']) ? sanitize_text_field(wp_unslash($_SERVER['REQUEST_URI'])) : '/';
    return $scheme . $host . $uri;
  }

  private function redirect_with_error(string $code, string $redirect = ''): void {
    $redirect = $redirect ?: $this->current_url();
    $url = add_query_arg('ffps_login_error', $code, $redirect);
    wp_safe_redirect($url);
    exit;
  }

  private function error_html(string $code): string {
    if ( ! $code ) return '';

    $map = [
      'invalid_nonce'  => 'Security check failed. Please try again.',
      'missing_fields' => 'Please fill in both fields.',
      'login_failed'   => 'Invalid login details. Please try again.',
    ];

    $msg = $map[$code] ?? 'Something went wrong. Please try again.';
    return '<div class="ffps-login-error" style="padding:10px;border:1px solid #d63638;background:#fff5f5;margin:10px 0;">'
      . esc_html($msg)
      . '</div>';
  }
}