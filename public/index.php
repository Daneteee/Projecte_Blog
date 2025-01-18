<?php include "includes/header.php"; ?>

<?php 
if (!isset($_SESSION)) {
    session_start();
}
?>

<div class="main-content container">
    <h1 class="page-title">Entrades del Blog</h1>
    <?php include 'entries/show_entries.php'; ?>
</div>

<?php include 'includes/sidebar.php'; ?>
<?php include "includes/footer.php"; ?>
