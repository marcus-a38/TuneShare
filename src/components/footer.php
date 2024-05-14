</main> <!-- div id="viewport" -->
    <footer class="container-fluid">
            <a href="#" class="nav-link">About</a>
            <span>&#x2022;</span>
            <a href="#" class="nav-link">Privacy Policy</a>
            <span>&#x2022;</span>
            <a href="#" class="nav-link">Terms of Service</a>
    </footer>
    <?php if (isset($_SESSION['userId'])): ?>
    <script>

        function toggleColor() {
            var toggler = document.getElementById("toggle-dark");
            if (toggler.getAttribute('src') === "../../img/moon.png") {
                toggler.src = "../../img/sun.png";
            } else {
                toggler.src = "../../img/moon.png";
            }
        }
        
    </script>
    <script src='../js/formAlerts.js'></script>
    <script src=../js/popups.js></script>
    <?php endif; ?>
</body>
</html>