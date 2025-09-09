<?php if (session()->getFlashdata('success')): ?>
    <div id="snackbar" class="show success"><?= session()->getFlashdata('success') ?></div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <div id="snackbar" class="show error"><?= session()->getFlashdata('error') ?></div>
<?php endif; ?>

<style>
#snackbar {
    visibility: hidden;
    min-width: 250px;
    background-color: #333;
    color: #fff;
    text-align: center;
    border-radius: 2px;
    padding: 16px;
    position: fixed;
    z-index: 9999;
    left: 50%;
    top: 30px;
    font-size: 17px;
    transform: translateX(-50%);
}

#snackbar.success {
    background-color: #4CAF50;
}

#snackbar.error {
    background-color: #f44336;
}
#snackbar.show {
    visibility: visible;
    -webkit-animation: fadein 0.5s, fadeout 0.5s 2.5s;
    animation: fadein 0.5s, fadeout 0.5s 2.5s;
}
@-webkit-keyframes fadein {
    from {top: 0; opacity: 0;}
    to {top: 30px; opacity: 1;}
}
@keyframes fadein {
    from {top: 0; opacity: 0;}
    to {top: 30px; opacity: 1;}
}
@-webkit-keyframes fadeout {
    from {top: 30px; opacity: 1;}
    to {top: 0; opacity: 0;}
}
@keyframes fadeout {
    from {top: 30px; opacity: 1;}
    to {top: 0; opacity: 0;}
}
</style>
<script>
window.onload = function() {
    var x = document.getElementById("snackbar");
    if (x) {
        if (!x.className.includes("show")) {
            x.className += " show";
        }
        setTimeout(function(){ x.className = x.className.replace("show", ""); }, 3000);
    }
};
</script> 