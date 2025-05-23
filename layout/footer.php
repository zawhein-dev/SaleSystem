</div>
</div>
</body>
</html>
<div class="modal modal-sm" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmation Message</h5>
                <button type="button" class="btn-close btn-sm" id="closeBtn" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure to delete..</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-sm btn-primary" id="deleteBtn">OK</button>
            </div>
        </div>
    </div>
</div>
<script src="../assets/js/bootstrap.min.js"></script>
<script src="../assets/js/jquery.min.js"></script>
<script>
    let deleteUser = $(".deleteUser");
    let deleteBtn = $("#deleteBtn");
    let closeBtn = $("#closeBtn");
    let deleteKey = null;
    deleteUser.on("click", function(e) {
        deleteKey = e.currentTarget.getAttribute("data-value");
        console.log("Value of deleteKey from deleteUser click:", deleteKey);
        key = deleteKey;
    })
    deleteBtn.on("click", () => {
        console.log("Value of deleteKey in deleteBtn click:", deleteKey);
       if(key == deleteKey){
            location.replace("?deleteId=" + key);
            closeBtn.click();
        } else {
            location.replace("?deleteError=" + deleteKey);
            closeBtn.click();
        }
    });
</script>
</body>
</html>