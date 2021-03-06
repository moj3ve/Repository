<!DOCTYPE html>
<?php

$package = $_GET['p'];
$info = json_decode(file_get_contents('data/'.$package.'.json'), true);
$version = end($info['changelog'])['version'];

$agent = strtolower(isset($_SERVER['HTTP_USER_AGENT'])?$_SERVER['HTTP_USER_AGENT']:"");
$ios = 0;
if(preg_match('/ip(hone|od|ad)/', $agent)){
  preg_match('/os (.+) like/', $agent,  $matches);
  $ios = floatval(str_replace('_', '.', $matches[1]));
}

if ($info['support_min']==null||$info['support_max']==null){
  $compatible = -1;
} else {
  $compatible = ($info['support_min']<=$ios&&$ios<=$info['support_max']) ? 1 : 0;
}

function toFixed1($num){
  $str = strval($num);
  if(strpos($str, ".")===false){
    $str .= ".0";
  }
  return $str;
}
?>
<html>
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <meta name="robots" content="noindex,nofollow" />
  <title><?php echo $info['name']; ?></title>

  <style>

*{
  margin: 0;
  padding:0;
  box-sizing: border-box;
  vertical-align: middle;
  -webkit-appearance:none;
}
html {
  font-size: 16px;
  font-family: sans-serif;
  color: #444;
}

body {
  width: 100vw;
}

.container {
  margin: 0 auto;
  max-width: 560px;
  width: 100%;
}

.container>div {
  width:100%;
  background: #f5f5f5;
  margin: 8px 0;
  padding: 8px 16px;
  border-style: solid;
  border-width: 0.5px 0;
  border-color: #888888;
}

.warning {
  color: #f1c40f;
  font-weight: bold;
  text-align: center;
}

.compatible {
  text-align: center;
  color: #fff;
  background: #e74c3c !important;
}

.compatible.ok{
  background: #27ae60 !important;
}

.screenshots {
  overflow: scroll;
  overflow-x: auto;
  white-space: nowrap;
  -webkit-overflow-scrolling: touch;
  overflow-scrolling: touch;
}

.screenshots>a>img {
  width: 160px;
  display: inline-block;
  margin: 16px;
}

.description {
}

.info>div {
  font-size: 12px;
  margin: 12px 0;
  text-align: right;
  position: relative;
}

.lbl {
  position: absolute;
  top: 0;
  left: 0;
  color: #555555;
}

.log {
  margin: 16px 0;
}
.log>p:first-child {
  font-weight: bold;
}
.log>p:last-child {
  margin-left: 16px;
}

.btn {
  position: relative;
  color: #fff;
  background: #f1c40f;
  border-radius: 8px;
  width: 100%;
  font-size: 20px;
  text-align: center;
  padding: 8px;
  margin: 4px;
}

.btn>a {
  position: absolute;
  top:0;
  left:0;
  width: 100%;
  height: 100%;
  z-index: 99;
}

.report {
  box-shadow: #e1b400 0 0 4px;
  background: #f1c40f;
}

.donate {
  box-shadow: #009688 0 0 4px;
  background: #009688;
}
  </style>

</head>
<body>
  <div class="container">

    <?php if ($info['info'] != null): ?>
    <div class="warning"><?php echo $info['info']; ?></div>
    <?php endif; ?>

    <?php if ($compatible == 1): ?>
    <div class="compatible ok">Compatible with iOS<?php echo toFixed1($ios); ?></div>
    <?php elseif ($compatible == 0): ?>
    <div class="compatible">Not compatible with iOS<?php echo toFixed1($ios); ?></div>
    <?php endif; ?>

    <?php if (count($info['screenshots'])): ?>
    <div class="screenshots">
      <?php foreach ($info['screenshots'] as $ss): ?>
      <a href="https://repo.4nni3.com/dp/ss/<?php echo $ss; ?>" href="_blank"><img src="https://repo.4nni3.com/dp/ss/<?php echo $ss; ?>"></a>
      <?php endforeach;  ?>
    </div>
    <?php endif; ?>

    <div class="description">
      <h3> Description </h3>
      <p><?php echo $info['description']; ?></p>
    </div>

    <div>
      <h3>Works?</h3>
      <div class="btn report"><a href="https://4nni3.com/report/?p=<?php echo $package; ?>&v=<?php echo $version; ?>" target="_blank"></a>Report</div>
      <p><a href="https://twitter.com/4nni3_?ref_src=twsrc%5Etfw" class="twitter-follow-button" data-show-count="false">Follow @4nni3_</a><script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script></p>
    </div>


    <div>
      <p>if you like, please donate.</p>
      <div class="btn donate"><a href="https://4nni3.com/donation/" target="_blank"></a> Donate </div>
    </div>

    <div id="changelog">
      <h3>Changelog</h3>

      <?php foreach($info['changelog'] as $log): ?>
      <div class="log">
        <p><?php echo $log['version'].' '.$log['date']; ?></p>
        <p><?php echo $log['description']; ?></p>
      </div>
      <?php endforeach;  ?>
    </div>

    <div class="info">
      <h3>Information</h3>

      <div><p class="lbl">PackageID</p><span><?php echo $package; ?></span></div>


      <div><p class="lbl">Version</p><span><?php echo $version; ?></span></div>


      <div><p class="lbl">Author</p><span><?php echo $info['author']; ?></span></div>

      <div><p class="lbl">Section</p><span><?php echo $info['section']; ?></span></div>


      <?php if ($compatible != -1): ?>
      <div><p class="lbl">Support</p><span id="support"><?php echo 'iOS '.toFixed1($info['support_min']).' - '.toFixed1($info['support_max']); ?></span></div>
      <?php endif; ?>

    </div>

  </div><!-- .container -->


</body>
</html>
