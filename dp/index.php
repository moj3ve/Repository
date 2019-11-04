<?php
$package = $_GET['p'];
$info = json_decode(file_get_contents('data/'.$package.'.json'), true);
$agent = strtolower(isset($_SERVER['HTTP_USER_AGENT'])?$_SERVER['HTTP_USER_AGENT']:"");
$ios = 0;
if(preg_match('/ip(hone|od|ad)/', $agent)){
  preg_match('/os (.+) like/', $agent,  $matches);
  $ios = floatval(str_replace('_', '.', $matches[1]));
}
if($info['support_min']==null||$info['support_max']==null){
  $compatible = -1;
}else{
  $compatible = ($info['support_min']<=$ios&&$ios<=$info['support_max'])?1:0;
}
function toFixed1($num){
  $str = strval($num);
  if(strpos($str, ".")===false){
    $str .= ".0";
  }
  return $str;
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width,initial-scale=1" />
  <meta name="robots" content="noindex,nofollow" />
	<title>dp</title>
	<link rel="stylesheet" type="text/css" href="https://repo.4nni3.com/dp/style.css" />
  <link rel="stylesheet" type="text/css" href="https://repo.4nni3.com/dp/form.css" />
</head>
<body ontouchstart="">
  <div class="container">

    <?php if ($info['info']!=null): ?>
    <div class="warning"><?php echo $info['info']; ?></div>
    <?php endif; ?>

    <?php if ($compatible==1): ?>
    <div class="compatible ok">Compatible with iOS<?php echo toFixed1($ios); ?></div>
    <?php elseif ($compatible==0): ?>
    <div class="compatible">Not compatible</div>
    <?php endif; ?>

    <?php if (count($info['screenshots'])): ?>
    <div class="screenshots">
      <?php foreach ($info['screenshots'] as $ss): ?>
      <img src="https://repo.4nni3.com/dp/ss/<?php echo $ss; ?>">
      <?php endforeach;  ?>
    </div>
    <?php endif; ?>

    <div class="description"><?php echo $info['description']; ?></div>

    <div>
      <div class="form">
        <textarea id="comment" placeholder="Message"></textarea>
        <div class="submit">
          <div id="works">Works</div>
          <div id="broken">Broken</div>
        </div>
        <div id="progress">
          <div id="bar"></div>
          <div id="cancel"></div>
        </div>
        <div id="done">Thank you!!
          <div><svg height="80px" viewBox="0 0 368 368" width="80px" xmlns="http://www.w3.org/2000/svg"><path fill="#fff" d="m328 0h-288c-22.054688 0-40 17.945312-40 40v224c0 22.054688 17.945312 40 40 40h24v56c0 3.230469 1.945312 6.160156 4.9375 7.390625.992188.417969 2.03125.609375 3.0625.609375 2.078125 0 4.128906-.816406 5.65625-2.34375l61.65625-61.65625h188.6875c22.054688 0 40-17.945312 40-40v-224c0-22.054688-17.945312-40-40-40zm24 264c0 13.230469-10.769531 24-24 24h-192c-2.128906 0-4.160156.839844-5.65625 2.34375l-50.34375 50.34375v-44.6875c0-4.425781-3.574219-8-8-8h-32c-13.230469 0-24-10.769531-24-24v-224c0-13.230469 10.769531-24 24-24h288c13.230469 0 24 10.769531 24 24zm0 0"/><path fill="#fff" d="m136 144c4.425781 0 8-3.574219 8-8v-64c0-4.425781-3.574219-8-8-8s-8 3.574219-8 8v64c0 4.425781 3.574219 8 8 8zm0 0"/><path fill="#fff" d="m232 144c4.425781 0 8-3.574219 8-8v-64c0-4.425781-3.574219-8-8-8s-8 3.574219-8 8v64c0 4.425781 3.574219 8 8 8zm0 0"/><path fill="#fff" d="m296 160c-4.425781 0-8 3.574219-8 8 0 30.871094-25.128906 56-56 56h-96c-30.871094 0-56-25.128906-56-56 0-4.425781-3.574219-8-8-8s-8 3.574219-8 8c0 39.703125 32.296875 72 72 72h96c39.703125 0 72-32.296875 72-72 0-4.425781-3.574219-8-8-8zm0 0"/></svg></div>
        </div>
      </div>
    </div>

    <div>
      不具合等ありましたら、上のフォームかTwitterでご連絡下さいm(_ _ )m <a href="https://twitter.com/4nni3_?ref_src=twsrc%5Etfw" class="twitter-follow-button" data-show-count="false">Follow @4nni3_</a><script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
    </div>

    <div>
      <a href="https://4nni3.com/donation/" target="_blank">Please Donate.</a>
    </div>

    <div id="changelog">
      <?php foreach($info['changelog'] as $log): ?>
      <div class="log">
        <p><?php echo $log['version'].' '.$log['date']; ?></p>
        <p><?php echo $log['description']; ?></p>
      </div>
      <?php endforeach;  ?>
    </div>

    <div class="info">
      <div><p class="k">PackageID</p><span id="packageid"><?php echo $package; ?></span></div>
      <div><p class="k">Version</p><span id="version"><?php echo end($info['changelog'])['version']; ?></span></div>
      <div><p class="k">Author</p><span id="author"><?php echo $info['author']; ?></span></div>
      <div><p class="k">Section</p><span id="section"><?php echo $info['section']; ?></span></div>
      <?php if($campatible!=-1): ?>
      <div><p class="k">Support iOS</p><span id="support"><?php echo 'iOS'.toFixed1($info['support_min']).' ~ iOS'.toFixed1($info['support_max']); ?></span></div>
      <?php endif; ?>
    </div>

  </div><!-- .container -->

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
  <script type="text/javascript">
  function submitForm(co){
    $('#progress').addClass("show");
    $("#bar").animate({width: '100%'}, 1500, 'linear', function(){
      $('#done').addClass("show");
      var comment = $('#comment').val();

      $.post(
      "https://docs.google.com/forms/u/1/d/e/1FAIpQLSchaB-HrJ0HYRuKROVKjkQjVAlNO6bSo8AmHikZz-2_MKXkvw/formResponse",
        {
          "entry.390043528": "<?php echo $package; ?>",
          "entry.1049998279": "<?php echo end($info['changelog'])['version']; ?>",
          "entry.178135634": "<?php echo $ios; ?>",
          "entry.1748641038": co,
          "entry.391198144": comment,
          dataType: "xml"
        }
      );
    });
  }
  $('#works').click(function(){ submitForm("動いた"); });
  $('#broken').click(function(){ submitForm("動かない"); });
  $('#cancel').click(function(){
    $("#bar").stop(true, false).css('width', "0%");
    $('#progress').removeClass("show");
    $('#done').removeClass("show");
  });
  </script>
</body>
</html>
