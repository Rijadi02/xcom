<script>
    function openErr()
    {
        document.getElementById("error-pop").style.display = "block";
        document.getElementById("error").innerHTML = '<?php echo substr($error, 0, -10);; ?>';
    }
    function closeErr()
    {
        document.getElementById("error-pop").style.display = "none";
    }
</script>

<?php 
if($error !== "")
{
    echo "<script type=\"text/javascript\">openErr()</script>";
}
?>

