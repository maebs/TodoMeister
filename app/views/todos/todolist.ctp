<script type="text/javascript">
	// JQuery on document readu function
	$(function() {
		// endable the sortable behavior
		<?php if($auth_level == 2) { ?>
		// add a callback to the sortable behavior
		$( ".sortable" ).sortable({
		   containment: 'parent',
		   update: function(event, ui) {
			$.ajax({
			   type: "POST",
			   url: "<?php echo $this->Html->url(array("action" => "order"));?>",
			   data: "item="+ui.item.attr('id')+"&order="+$(this).sortable( "toArray" ),
				success: function(html){
					$currentVersion = "-1";
				  }
			 });
			}

		});

		// edit a todoitem when a user clicks the text
		$('.todo.editable').dblclick(function() {
			if($(this).hasClass('editable')){
				$(this).removeClass('editable');
				id = $(this).parent().attr('id');
				text = $(this).html();
				text = text.replace(/<br>/gi, "")
				replacetext = '';
				replacetext += '<form method="post" action="<?php echo $this->Html->url(array("action" => "update",$project_id,$name));?>">';
				replacetext += '<textarea name="todo" type="text">'+text+'</textarea>';
				replacetext += '<input name="id" type="hidden" value="'+id+'">';
				replacetext += '<input type="submit" value="save" / >';
				replacetext += '</form>';
				$(this).html(replacetext);
				$(this).parent().children('.actions').hide();
				updateColorPickers();
			};
		});


		
		// handle color changes
		$('.color').change(function() {			
			var id =  $(this).attr('name');
			var color =  $(this).attr('value');

			$.ajax({
			   type: "POST",
			   url: "<?php echo $this->Html->url(array("action" => "color"));?>/"+id,
			   data: "color="+color,
				success: function(html){
					$currentVersion = "-1";
				  }
			 });
		});

		<?php } ?>
		//set title of the HTML page
		$( "title" ).html('<?php echo $project_id . " - " .$name; ?> [<?php echo count($statusOne); ?>,<?php echo count($statusTwo); ?>,<?php echo count($statusThree); ?>]');


		// the url can contain a # and a status number, this gives input to right field
		urlparts = window.location.href.split('#');
		urlparts.shift();
		if(urlparts.length > 0){
			selector = '.column.status'+urlparts[0]+' input:text.todo';
			$(selector).focus();
		}

	});

	$currentVersion = "-1";

	function checkVersion(){

		$.ajax({
		  url: "<?php echo $this->Html->url(array("action" => "version",$project_id));?>",
		  cache: false,
		  success: function(html){
				if($currentVersion == "-1"){
					$currentVersion = html;
				}else if($currentVersion != html){
					window.location.reload();
				}
		  }

		});
		setTimeout(checkVersion, 10000);
	}

	checkVersion();
</script>





<div class="column status1">
	<div class="title"><h1>todo (<?php echo count($statusOne); ?>)</h1></div>
	<?php if($auth_level == 2) { ?>
	<div class="form">
 		<form method="post" action="<?php echo $this->Html->url(array("action" => "add",$project_id,$name));?>">
			<input type="text" name="data[Todo][text]" class="input todo"/>
			<input type="hidden" name="data[Todo][project_id]" value="<?php echo $project_id; ?>" class="input"/>
			<input type="hidden" name="data[Todo][who]" value="<?php echo $name; ?>" class="input"/>
			<input type="hidden" name="data[Todo][status]" value="1" class="input"/>
			<input type="submit" class="button" value="add" />
		</form>
	</div>
	<?php } ?>
	<ul class="sortable">
		<?php foreach ($statusOne as $status) { ?>
			<?php echo $this->Todo->render($status,$project_id,$name,$auth_level); ?>
		<?php } ?>
	</ul>
</div>
<div class="column status2">
	<div class="title"><h1>in progress (<?php echo count($statusTwo); ?>)</h1></div>
	<?php if($auth_level == 2) { ?>
	<div class="form">
 		<form method="post" action="<?php echo $this->Html->url(array("action" => "add",$project_id,$name));?>">
			<input type="text" name="data[Todo][text]" class="input todo"/>
			<input type="hidden" name="data[Todo][project_id]" value="<?php echo $project_id; ?>" class="input"/>
			<input type="hidden" name="data[Todo][who]" value="<?php echo $name; ?>" class="input"/>
			<input type="hidden" name="data[Todo][status]" value="2" class="input"/>
			<input type="submit" class="button" value="add" />
		</form>
	</div>
	<?php } ?>
	<ul class="sortable">
		<?php foreach ($statusTwo as $status) { ?>
			<?php echo $this->Todo->render($status,$project_id,$name,$auth_level); ?>
		<?php } ?>
	</ul>
</div>
<div class="column status3">
	<div class="title status3"><h1>done (<?php echo count($statusThree); ?>)</h1></div>
	<ul>
		<?php 
			$iterator = 0;
			foreach ($statusThree as $status) { 
				$iterator++;
				if($iterator < 10 || $this->Session->read('more') == 1){
					echo $this->Todo->render($status,$project_id,$name,$auth_level); 
				}else{ 
					?>	<div class="more"><a href="<?php echo $this->Html->url(array("action" => "more"));?>">more...</a></div><?php 
				break;
				}
 			}
			if($iterator > 10 && $this->Session->read('more') == 1){
				?>	<div class="more"><a href="<?php echo $this->Html->url(array("action" => "less"));?>">less...</a></div><?php 	
			}
 			?>
	</ul>
</div>
