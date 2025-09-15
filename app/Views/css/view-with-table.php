<style>
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 30px;
    }
    
    .title-section {
        flex: 1;
    }
    
    .page-title {
        font-size: 2.5em;
        color: <?= MAIN_DARK_COLOR; ?>;
        margin: 0 0 10px 0;
    }
    
    .page-subtitle {
        font-size: 1.1em;
        color: <?= GRAY; ?>;
        margin: 0;
        font-weight: 400;
    }
    
    .create-btn {
        background-color: transparent;
        color: <?= MAIN_DARK_COLOR; ?>;
        border: 2px solid <?= MAIN_DARK_COLOR; ?>;
        padding: 12px 24px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
        outline: none;
    }
    
    .create-btn:hover {
        background-color: <?= MAIN_DARK_COLOR; ?>;
        color: <?= WHITE; ?>;
    }
    
    .table {
        width: 100%;
        border-collapse: collapse;
        color: <?= WHITE; ?>;
        table-layout: fixed;
    }
    
    .table th:nth-child(1),
    .table td:nth-child(1) {
        width: 80px;
        text-align: center;
        border-radius: 10px 0 0 0;
        border-right: 1px solid <?= VIOLET_ACCENT; ?>;
    }
    
    .table th {
        background-color:  <?= VIOLET_ACCENT; ?>;
        padding: 15px;
        text-align: left;
        font-weight: 600;
    }
    
    .table td {
        background-color:  <?= MAIN_VERY_LIGHT_COLOR; ?>;
        padding: 15px;
        color: <?= MAIN_DARK_COLOR; ?>;
        border-bottom: 1px solid <?= VIOLET_ACCENT; ?>;
    }
    
    .action-buttons {
        display: flex;
        gap: 8px;
    }
    
    .btn-edit, .btn-delete {
        padding: 6px 12px;
        border-radius: 8px;
        cursor: pointer;
        text-decoration: none;
        display: inline-block;
        text-align: center;
        min-width: 60px;
    }
    .btn-edit {
        color: <?= MAIN_DARK_COLOR; ?>;
    }
    .btn-delete {
        color: <?= DANGER; ?>;
    }
    .btn-edit:hover {
        background-color: <?= MAIN_DARK_COLOR; ?>;
        color: <?= WHITE; ?>;
    }
    
    .btn-delete:hover {
        background-color: <?= DANGER_DARK_COLOR; ?>;
        color: <?= WHITE; ?>;
    }
    
    .no-data {
        text-align: center;
        padding: 60px 20px;
        color: <?= VIOLET_ACCENT; ?>;
        font-size: 1.2em;
    }
    
    .no-data-icon {
        font-size: 3em;
        margin-bottom: 20px;
        opacity: 0.7;
    }

    .table td.no-data-cell {
        border: none !important;
        border-radius: 0 !important;
        background-color: transparent !important;
    }
</style>