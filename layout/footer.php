</div>
</div>
</body>

</html>
<script>
    let deleteUser = $(".deleteUser");
    let deleteBtn = $("#deleteBtn");
    let closeBtn = $("#closeBtn");
    deleteUser.on("click", function(e) {
        deleteKey = e.currentTarget.getAttribute("data-value");
    })
    deleteBtn.on("click", () => {
        location.replace("?deleteId=" + deleteKey);
        closeBtn.click();
    })
</script>
</body>
</html>