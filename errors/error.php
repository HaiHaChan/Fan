<?php include '../view/header.php'; ?>
<div id="content">
    <h2>Error</h2>
    <p><?php 
    if(!empty($error_message)){?>
    	<ul>
			<?php foreach ($error_message as $value) : ?>
				<li><?= $value ?></li>
			<?php endforeach; ?>
		</ul>
    <?php }?>
</div>
<?php include '../view/footer.php'; ?>