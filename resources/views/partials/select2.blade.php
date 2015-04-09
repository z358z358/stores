<!--select2-->
<link rel="stylesheet" type="text/css" href="{{ url( elixir('css/select2.css') ) }}">
<script type="text/javascript">
	var select2_placeholder_text = "{!! isset($select2_placeholder_text) ? $select2_placeholder_text : '選擇...' !!}";
</script>
<script src="{{ url( elixir('js/select2.js') ) }}" type="text/javascript"></script>
<!--END select2-->
