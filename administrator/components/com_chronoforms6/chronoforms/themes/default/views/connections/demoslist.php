<?php
/* @copyright:ChronoEngine.com @license:GPLv2 */defined('_JEXEC') or die('Restricted access');
defined("GCORE_SITE") or die;
?>

<form action="<?php echo r2('index.php?ext=chronoforms&cont=connections&act=demoslist'); ?>" method="post" name="admin_form" id="admin_form" enctype="multipart/form-data" class="ui form">
	
	<h2 class="ui header"><?php el('Select a demo form'); ?></h2>
	
	<div class="ui clearing divider"></div>
	
	<div class="ui bottom attached tab segment active" data-tab="general">
		<?php
			$demos = [];
			
			$path = \G2\L\Extension::getInstance('chronoforms')->path();
			$path = $path.'demos'.DS;
			$d = dir($path);
			while(false !== ($entry = $d->read())){
				
				$filepath = $path.$entry;
				if(is_file($filepath)){
					$data = file_get_contents($filepath);
					$form = json_decode($data, true);
					//pr($form);
					$name = explode('.', basename($filepath))[0];
					$demos[$name] = [
						'name' => $form[0]['Connection']['title'],
						'description' => $form[0]['Connection']['description'],
					];
				}
			}
			
		?>
		<div class="ui ordered fluid vertical steps">
			<?php foreach($demos as $name => $data): ?>
				<div class="step">
					<div class="content">
						<a class="title" href="<?php echo r2('index.php?ext=chronoforms&cont=connections&act=demos&name='.$name); ?>"><?php echo $data['name']; ?></a>
						<div class="description"><?php echo nl2br($data['description']); ?></div>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
		
	</div>
	
</form>
