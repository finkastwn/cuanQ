<style>
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
    }
    
    .modal-content {
        background-color: <?= WHITE; ?>;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        padding: 30px;
        border-radius: 12px;
        width: 90%;
        max-width: 500px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
    }
    
    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
    }
    
    .modal-title {
        font-size: 1.5em;
        color: <?= MAIN_DARK_COLOR; ?>;
        margin: 0;
        font-weight: 600;
    }
    
    .close {
        color: <?= DANGER; ?>;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
        line-height: 1;
    }
    
    .close:hover {
        color: <?= DANGER_DARK_COLOR; ?>;
    }
    
    .form-group {
        margin-bottom: 20px;
    }
    
    .form-label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: <?= MAIN_DARK_COLOR; ?>;
    }
    
    .form-input {
        width: 100%;
        padding: 12px 16px;
        border: 2px solid <?= LIGHT_GRAY; ?>;
        border-radius: 8px;
        font-size: 16px;
        transition: border-color 0.3s ease;
        box-sizing: border-box;
        margin-bottom: 15px;
    }
    
    .form-input:focus {
        outline: none;
        border-color: <?= MAIN_COLOR; ?>;
    }
    
    .form-hint {
        font-size: 14px;
        color: <?= GRAY; ?>;
        margin-top: 5px;
        font-style: italic;
    }
    
    .modal-actions {
        display: flex;
        gap: 15px;
        justify-content: flex-end;
        margin-top: 30px;
    }
    
    .btn-cancel {
        background-color: transparent;
        color: <?= DANGER_DARK_COLOR; ?>;
        border: 2px solid <?= DANGER_DARK_COLOR; ?>;
        padding: 10px 20px;
        border-radius: 6px;
        cursor: pointer;
    }
    
    .btn-cancel:hover {
        background-color: <?= DANGER_DARK_COLOR; ?>;
        color: <?= WHITE; ?>;
    }
    
    .btn-submit {
        background-color: transparent;
        color: <?= MAIN_DARK_COLOR; ?>;
        border: 2px solid <?= MAIN_DARK_COLOR; ?>;
        padding: 10px 20px;
        border-radius: 6px;
        text-decoration: none;
        cursor: pointer;
    }
    
    .btn-submit:hover {
        background-color: <?= MAIN_DARK_COLOR; ?>;
        color: <?= WHITE; ?>;
    }
    
    .currency-input-wrapper {
        position: relative;
        display: flex;
        align-items: center;
    }
    
    .currency-prefix {
        position: absolute;
        left: 16px;
        top: 50%;
        transform: translateY(-100%);
        color: <?= MAIN_DARK_COLOR; ?>;
        font-weight: 600;
        font-size: 16px;
        z-index: 1;
        pointer-events: none;
        line-height: 1;
    }
    
    .currency-input {
        padding-left: 45px !important;
    }
</style>