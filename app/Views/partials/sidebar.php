<link href="https://fonts.googleapis.com/css2?family=Raleway:wght@700&display=swap" rel="stylesheet">
<style>
    body {
        margin: 0;
    }
    .sidebar {
        position: fixed;
        top: 0;
        left: 0;
        width: 220px;
        height: 100%;
        background-color: <?= WHITE; ?>;
        color: <?= MAIN_DARK_COLOR; ?>;
        display: flex;
        flex-direction: column;
        border-right: 0.1px outset <?= LIGHT_GRAY; ?>;
    }
    .sidebar-content {
        padding-top: 0;
        margin: 0;
        text-align: left;
        display: block;
        margin-top: 0;
    }
    .sidebar-content a {
        text-align: left;
        margin: 0;
    }
    .sidebar h2 {
        text-align: center;
        margin-bottom: 30px;
        font-size: 1.5em;
        letter-spacing: 1px;
    }
    .sidebar a:not(.logout-btn) {
        display: block;
        color: <?= MAIN_COLOR; ?>;
        padding: 15px 30px;
        text-decoration: none;
        transition: background 0.2s;
    }
    .sidebar > .sidebar-content > a:hover,
    .sidebar > .sidebar-content > .dropdown > a:hover {
        background-color: <?= MAIN_COLOR; ?>;
        color: <?= WHITE; ?>;
        border-radius: 20px;
        margin: 0 10px;
    }
    .sidebar a.active {
        background-color: <?= MAIN_DARK_COLOR; ?>;
        color: <?= WHITE; ?>;
        border-radius: 20px 20px 20px 20px;
        margin-right: 10px;
        margin-left: 10px;
    }
    .logout-btn {
        margin: 20px 20px 20px 20px;
        padding: 10px 20px;
        background-color: <?= DANGER; ?>;
        color: <?= WHITE; ?>;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        text-decoration: none;
        display: block;
        text-align: center;
        margin-top: auto;
    }
    .logout-btn:hover {
        background-color: <?= DANGER_DARK_COLOR; ?>;
        color: <?= WHITE; ?>;
    }
    .main-content {
        margin-left: 220px;
        padding: 40px 20px;
    }
    .welcome-text {
        margin-top: 50px;
        text-align: center;
    }
    .raleway-title {
        font-family: 'Raleway', Arial, Helvetica, sans-serif !important;
        font-weight: 700 !important;
        font-style: normal !important;
        font-size: 48px !important;
        margin-top: 20px !important;
        padding-top: 0 !important;
    }
    .text-left {
        text-align: left !important;
        margin-left: 30px !important;
    }
    img {
        border-radius: 50%;
        width: 40px;
        height: 40px;
    }
    .user-section {
        display: flex;
        align-items: center;
        margin: 20px 30px;
        gap: 10px;
    }
    .user-section h3 {
        margin: 0;
        font-size: 18px;
    }
    
    .dropdown {
        display: block;
    }
    
    .dropdown-content {
        display: none;             
        position: relative;        
        border-radius: 0 0 5px 5px;
        width: 100%;              
        box-shadow: none; 
        margin: 0;                 
        padding: 0;                
    }
    
    .dropdown:hover .dropdown-content {
        display: block;
    }
    
    .dropdown-content a {
        padding: 12px 20px;
        display: block;
        color: <?= MAIN_COLOR; ?> !important;
        text-decoration: none;
        border-bottom: 1px solid rgba(255,255,255,0.1);
        margin: 10px;
        border-radius: 20px      
    }                       
    
    .dropdown-content a:last-child {
        border-bottom: none;
    }
    
    .dropdown-content a:hover {
        color: <?= MAIN_DARK_COLOR; ?> !important;
        background-color: <?= LIGHT_GRAY; ?>;
    }
    
    .dropdown > a::after {
        content: '▼';
        float: right;
        font-size: 12px;
    }
</style>
<?php
if (!isset($activeMenu)) $activeMenu = '';
?>
<div class="sidebar">
    <h2 class="raleway-title">CuanQ</h2>
    <div class="user-section">
        <img src="/img/img_avatar.png" alt="avatar">
        <h3>Hi, <?= session()->get('name') ?>!</h3>
    </div>
    <div class="sidebar-content">
        <a href="/" class="<?= $activeMenu === 'dashboard' ? 'active' : '' ?>">Dashboard</a>
        <!-- <div class="dropdown">
            <a href="" class="<?= $activeMenu === 'categories' ? 'active' : '' ?>">Categories</a>
            <div class="dropdown-content">
       
            </div>
        </div> -->
        <!-- Add more links as needed -->
        <a href="/pesanan" class="<?= $activeMenu === 'pesanan' ? 'active' : '' ?>">Pesanan</a>
        <a href="/produk" class="<?= $activeMenu === 'produk' ? 'active' : '' ?>">Produk</a>
        <a href="/bahan-baku" class="<?= $activeMenu === 'bahan_baku' ? 'active' : '' ?>">Bahan Baku</a>
        <a href="/pembelian-bahan" class="<?= $activeMenu === 'pembelian' ? 'active' : '' ?>">Pembelian Bahan</a>
        <a href="/" class="<?= $activeMenu === 'keuangan' ? 'active' : '' ?>">Keuangan</a>
    </div>
    <a href="/logout" class="logout-btn">Logout</a>
</div>