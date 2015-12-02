<!-- footer -->
<script src="{{ url( elixir('js/all.js') ) }}" type="text/javascript"></script>

<script type="text/javascript">
  var __act = ("string" == typeof __act) ? __act : "";
	$(function() {
		$('#flash-overlay-modal').modal();
    jQuery.timeago.settings.localeTitle = true;
		$(".timeago").timeago();
    $(".bind-form").on("submit", ".form-ajax", function(e){
      e.preventDefault();
      var form = $(this);
      $.post(form.attr("action"), form.serialize(), function(data){
        vue.ajax_result(data);
      }, "json");
      return false;
    });
    $('.bootstrap-multiselect').multiselect();
	});
</script>

<!-- fb -->
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/zh_TW/sdk.js#xfbml=1&appId={{ Config::get('oauth-5-laravel.consumers.Facebook.client_id') }}&version=v2.0";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
<!-- end fb -->

@if(Config::get('app.debug'))
<script type="text/javascript">
    var queries = {!! json_encode(DB::getQueryLog()) !!};
    console.log('/****************************** Database Queries ******************************/');
    console.log(' ');
    queries.forEach(function(query) {
        console.log('   ' + query.time + ' | ' + query.query + ' | ' + query.bindings[0]);
    });
    console.log(' ');
    console.log('/****************************** End Queries ***********************************/');
</script>
@endif
<!-- footer END -->