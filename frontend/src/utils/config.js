let ajaxurl = window.ajaxurl

if (window.ajaxurl) {
  ajaxurl = window.ajaxurl
} else if (import.meta.env.MODE === 'development' && import.meta.env.VITE_WP_AJAX) {
  ajaxurl = import.meta.env.VITE_WP_AJAX
}

export default {
  ajaxurl
}
