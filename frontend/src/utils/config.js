let ajaxurl = window.ajaxurl

if (import.meta.env.VITE_WP_AJAX) {
  ajaxurl = import.meta.env.VITE_WP_AJAX
}

export default {
  ajaxurl
}
