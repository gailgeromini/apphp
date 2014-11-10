<!doctype html>
<html>
<head>
    <meta charset="UTF-8" />
	<meta name="keywords" content="<?php echo CHtml::encode($this->_pageKeywords); ?>" />
	<meta name="description" content="<?php echo CHtml::encode($this->_pageDescription); ?>" />
    <title><?php echo CHtml::encode($this->_pageTitle); ?></title>
    <?php echo CHtml::faviconFile("templates/favicon.ico"); ?>
    <base href="<?php echo A::app()->getRequest()->getBaseUrl(); ?>" />

    <?php echo CHtml::cssFile("templates/micro/css/main.css"); ?>
    <?php echo CHtml::cssFile("templates/micro/css/skin_clean.css"); ?>

	<?php echo CHtml::scriptFile('templates/micro/js/jquery.js'); ?>
	<?php echo CHtml::scriptFile('templates/micro/js/micro.js'); ?>
	<?php echo CHtml::scriptFile('templates/micro/js/validate.form.req.js'); ?>
	<script type="text/javascript">
		var reqForm = new Validate();
	</script>
</head>

    <?php echo A::app()->view->getContent(); ?>

</html>