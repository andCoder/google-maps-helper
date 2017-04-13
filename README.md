# Google Maps Helper
<p>
  Before usage you need to set required options. To do that go to <b>Settings > Google Maps Helper</b> Here you can set an additional options such as marker or choose json fields to display.  
</p>
<p>This is a Wordpress plugin for managing Google Maps View.
It uses a shortcode <b>[google-maps-helper]</b>. This shortcode can be used with these attributes:</p> 
<ol>
  <li><b>title</b></li>
  <li><b>type</b> - can be: <i>roadmap</i>, <i>satellite</i>, <i>hybrid</i> or <i>terrain</i></li>
  <li><b>refresh_interval</b></li>
  <li><b>zoom (number)</b></li>
  <li><b>map_center</b> - a string in format of <i>"latitude;longitude"</i></li>
</ol>  
<p><b>InfoWindows</b> appears when you hover a marker. You can change its template modifing <i>"info_window.tpl"</i> file in <i>"includes"</i> folder. Or you can override its template by adding filter for <i>"gmh_before_print_info_window"</i> that uses $content as an argument. Just use an html markup. You need to wrap required property by "%" tag. Every field that you have to display must exists in json.</p>
