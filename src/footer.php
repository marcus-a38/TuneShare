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
        function openPopup() {
            var popup = document.getElementById("popup");
            popup.classList.toggle("show");
        }
    </script>
    <?php endif; ?>
</body>
</html>