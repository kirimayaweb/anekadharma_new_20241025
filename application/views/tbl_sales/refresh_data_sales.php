<!DOCTYPE html>
<html>
<head>
    <title>Autorefresh Browser using jquery</title>
    <!-- <script type="text/javascript" src="jquery.min.js"></script> -->
    <!-- jQuery -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.3/jquery.min.js"></script>

    <script type="text/javascript">
        $(function() {
            startRefresh();
        });
        function startRefresh() {
            setTimeout(startRefresh,100);
            $.get('text.html', function(data) {
                $('#viewHere').html(data);
            });
        }
    </script>

</head>
<body>


    <?php echo "Tes"; ?>
    <div id="viewHere"></div>
</body>
</html>