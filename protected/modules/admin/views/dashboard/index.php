<h1>Admin</h1>
<p>this is the dashboard</p>


<p>possible warnigns or messages


<h3>Quick Links</h3>
<ul>
	<li><?php echo CHtml::link('Create new Post', array('/admin/posts/create')); ?>
	<li><?php echo CHtml::link('Upload a File', array('/admin/files/upload')); ?>
	<li><?php echo CHtml::link('Approve Comments', array('/admin/comments/unapproved')); ?>
</ul>

<h3>Settings</h3>
<ul>
	<li><?php echo CHtml::link('Home Page Settings', array('/admin/settings')); ?>
</ul>
