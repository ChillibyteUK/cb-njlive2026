(function(){
  try {
    if (!window.cbThreeBg || !window.cbThreeBg.url) return;
    var url = window.cbThreeBg.url;
    // Append version for cache-busting if provided
    if (window.cbThreeBg.version) {
      var sep = url.indexOf('?') === -1 ? '?' : '&';
      url += sep + 'ver=' + encodeURIComponent(window.cbThreeBg.version);
    }
    import(url).catch(function(err){
      console.error('Failed to import three-bg module:', err);
    });
  } catch (e) {
    console.error('Three module loader error:', e);
  }
})();
