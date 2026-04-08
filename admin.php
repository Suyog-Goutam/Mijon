<?php
session_start();

// Hardcoded admin credentials
$admin_user = 'admin';
$admin_pass = 'admin';

// Handle login
if (isset($_POST['login'])) {
    if ($_POST['username'] === $admin_user && $_POST['password'] === $admin_pass) {
        $_SESSION['loggedin'] = true;
    } else {
        $error = "Invalid username or password";
    }
}

// Handle logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: admin.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mijon Admin Dashboard</title>
    <link rel="icon" type="image/png" href="images/favicon.png">
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Outfit:wght@500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
        <style>
        :root {
            /* Premium Color Palette */
            --color-primary: #0F172A; /* Deep Slate */
            --color-primary-light: #1E293B;
            --color-accent: #059669; /* Emerald */
            --color-accent-hover: #047857;
            --color-gold: #D4AF37; /* Clinical Gold */
            --color-bg: #F8FAFC; /* Soft cool gray for background */
            --color-surface: #FFFFFF;
            --color-border: #E2E8F0;
            --color-text: #334155;
            --color-text-muted: #64748B;
            
            /* Typography */
            --font-sans: 'Inter', system-ui, -apple-system, sans-serif;
            --font-display: 'Outfit', system-ui, sans-serif;
            
            /* Premium Soft Shadows */
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
            --shadow-md: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.02);
            --shadow-lg: 0 20px 25px -5px rgba(0, 0, 0, 0.05), 0 10px 10px -5px rgba(0, 0, 0, 0.02);
            --shadow-glass: 0 8px 32px 0 rgba(31, 38, 135, 0.15);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: var(--font-sans); background-color: var(--color-bg); color: var(--color-text); line-height: 1.6; -webkit-font-smoothing: antialiased; }

        /* =========================================
           LOGIN SCREEN 
           ========================================= */
        .login-wrapper { display: flex; align-items: center; justify-content: center; min-height: 100vh; background: linear-gradient(135deg, #0F172A 0%, #1E293B 100%); position: relative; overflow: hidden; }
        .login-wrapper::before { content: ''; position: absolute; width: 400px; height: 400px; background: rgba(5, 150, 105, 0.15); border-radius: 50%; top: -100px; left: -100px; filter: blur(60px); }
        .login-wrapper::after { content: ''; position: absolute; width: 300px; height: 300px; background: rgba(212, 175, 55, 0.15); border-radius: 50%; bottom: -50px; right: -50px; filter: blur(60px); }
        
        .login-card { 
            background: rgba(255, 255, 255, 0.95); 
            backdrop-filter: blur(10px);
            padding: 3.5rem 3rem; 
            border-radius: 20px; 
            box-shadow: var(--shadow-glass); 
            border: 1px solid rgba(255, 255, 255, 0.2);
            width: 100%; 
            max-width: 420px; 
            position: relative;
            z-index: 10;
        }
        .login-logo { text-align: center; font-family: var(--font-display); font-size: 2rem; color: var(--color-primary); margin-bottom: 2.5rem; font-weight: 700; letter-spacing: -0.5px; }
        .login-logo span { color: var(--color-accent); }
        .login-logo img { width: 32px; height: 32px; object-fit: contain; margin-right: 0.5rem; vertical-align: bottom; }
        .form-group { margin-bottom: 1.5rem; }
        .form-label { display: block; margin-bottom: 0.5rem; font-weight: 600; font-size: 0.85rem; color: var(--color-text); text-transform: uppercase; letter-spacing: 0.5px; }
        .form-control { width: 100%; padding: 0.85rem 1.125rem; border: 1.5px solid var(--color-border); border-radius: 10px; font-family: inherit; font-size: 1rem; transition: all 0.2s; outline: none; background: #F8FAFC; color: var(--color-text); }
        .form-control:focus { border-color: var(--color-accent); background: white; box-shadow: 0 0 0 4px rgba(5, 150, 105, 0.1); }
        .btn-submit { display: block; width: 100%; padding: 0.875rem; background: var(--color-primary); color: white; border: none; border-radius: 10px; font-weight: 600; font-family: inherit; font-size: 1.05rem; cursor: pointer; transition: all 0.2s ease; margin-top: 2rem; box-shadow: 0 4px 6px rgba(15, 23, 42, 0.2); }
        .btn-submit:hover { background: var(--color-primary-light); transform: translateY(-1px); box-shadow: 0 6px 12px rgba(15, 23, 42, 0.25); }
        .error-msg { background: #FEF2F2; color: #DC2626; padding: 1rem; border-radius: 8px; font-size: 0.9rem; margin-bottom: 1.5rem; border: 1px solid #F87171; text-align: center; font-weight: 500; }

        /* =========================================
           DASHBOARD LAYOUT 
           ========================================= */
        .dashboard-layout { display: flex; min-height: 100vh; }
        
        /* Sidebar */
        .sidebar { width: 280px; min-width: 280px; flex-shrink: 0; background: var(--color-primary); color: white; display: flex; flex-direction: column; position: sticky; top: 0; height: 100vh; box-shadow: var(--shadow-md); z-index: 50; }
        .sidebar-header { padding: 1.75rem 1.5rem; font-family: var(--font-display); font-size: 1.5rem; font-weight: 700; color: white; border-bottom: 1px solid rgba(255,255,255,0.05); }
        .sidebar-header span { color: var(--color-accent); }
        .sidebar-header img { width: 26px; height: 26px; object-fit: contain; margin-right: 0.5rem; vertical-align: middle; }
        .sidebar-nav { flex-grow: 1; padding: 1.5rem 0; display: flex; flex-direction: column; gap: 0.25rem; }
        .nav-item { display: flex; align-items: center; padding: 0.875rem 1.5rem; margin: 0 1rem; color: rgba(255,255,255,0.7); text-decoration: none; transition: all 0.2s; font-weight: 500; border-radius: 8px; box-sizing: border-box; }
        .nav-item i { width: 24px; font-size: 1.25rem; margin-right: 0.75rem; text-align: center; transition: color 0.2s; }
        .nav-item:hover { background: rgba(255,255,255,0.05); color: white; }
        .nav-item.active { background: rgba(5, 150, 105, 0.15); color: white; color: var(--color-accent); }
        .nav-item.active i { color: var(--color-accent); }
        
        .sidebar-footer { padding: 1.5rem; border-top: 1px solid rgba(255,255,255,0.05); }
        .btn-logout { display: flex; align-items: center; justify-content: center; width: 100%; padding: 0.875rem; background: rgba(255,255,255,0.05); color: white; text-decoration: none; border-radius: 8px; font-weight: 600; transition: all 0.2s; border: 1px solid rgba(255,255,255,0.1); }
        .btn-logout i { margin-right: 0.5rem; }
        .btn-logout:hover { background: #DC2626; border-color: #DC2626; transform: translateY(-1px); }

        /* Main Content */
        .main-content { flex-grow: 1; min-width: 0; display: flex; flex-direction: column; overflow: hidden; background: var(--color-bg); }
        .topbar { background: var(--color-surface); height: 80px; border-bottom: 1px solid var(--color-border); display: flex; align-items: center; justify-content: space-between; padding: 0 2.5rem; z-index: 40; }
        .page-title { font-family: var(--font-display); font-size: 1.4rem; font-weight: 700; color: var(--color-primary); letter-spacing: -0.5px; }
        
        /* Current Date Badge */
        .date-badge { background: #F1F5F9; padding: 0.6rem 1.25rem; border-radius: 9999px; font-size: 0.875rem; color: var(--color-text-muted); font-weight: 600; display: flex; align-items: center; border: 1px solid #E2E8F0; }

        /* Dashboard Body */
        .content-body { padding: 2.5rem; overflow-y: auto; }
        
        /* Stats row */
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem; margin-bottom: 2.5rem; }
        .stat-card { background: var(--color-surface); padding: 1.75rem; border-radius: 16px; box-shadow: var(--shadow); border: 1px solid var(--color-border); display: flex; align-items: center; transition: transform 0.2s, box-shadow 0.2s; position: relative; overflow: hidden; }
        .stat-card:hover { transform: translateY(-2px); box-shadow: var(--shadow-md); }
        /* Add subtle gleam to stats */
        .stat-card::after { content:''; position:absolute; top:0; left:0; right:0; height:4px; opacity:0.8; }
        .stat-card:nth-child(1)::after { background: var(--color-accent); }
        .stat-card:nth-child(2)::after { background: var(--color-gold); }
        .stat-card:nth-child(3)::after { background: #3B82F6; }

        .stat-icon { width: 64px; height: 64px; border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 1.75rem; margin-right: 1.25rem; box-shadow: var(--shadow-sm); }
        .icon-primary { background: linear-gradient(135deg, #10B981, #059669); color: white; }
        .icon-accent { background: linear-gradient(135deg, #FCD34D, #D4AF37); color: white; }
        .icon-blue { background: linear-gradient(135deg, #60A5FA, #3B82F6); color: white; } /* Reused chat-lead */
        
        .stat-label { color: var(--color-text-muted); font-size: 0.9rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.25rem; }
        .stat-value { font-family: var(--font-display); font-size: 2.5rem; font-weight: 700; color: var(--color-primary); line-height: 1; letter-spacing: -1px; }

        /* Table Card (Data Grid) */
        .table-card { background: var(--color-surface); border-radius: 16px; box-shadow: var(--shadow); border: 1px solid var(--color-border); overflow: hidden; margin-bottom: 2rem; }
        .table-header { padding: 1.5rem 1.75rem; border-bottom: 1px solid var(--color-border); display: flex; justify-content: space-between; align-items: center; background: rgba(248, 250, 252, 0.5); }
        .table-title { font-weight: 700; font-size: 1.15rem; color: var(--color-primary); display: flex; align-items: center; font-family: var(--font-display); letter-spacing: -0.3px; }
        .table-title i { margin-right: 0.75rem; color: var(--color-accent); font-size: 1.2rem; }
        
        table { width: 100%; border-collapse: separate; border-spacing: 0; text-align: left; }
        th { padding: 1rem 1.75rem; background: #F8FAFC; font-weight: 600; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.05em; color: var(--color-text-muted); border-bottom: 1px solid var(--color-border); }
        td { padding: 1.25rem 1.75rem; border-bottom: 1px solid var(--color-border); font-size: 0.95rem; vertical-align: top; color: var(--color-text); transition: background 0.15s; }
        tr:last-child td { border-bottom: none; }
        
        .badge { display: inline-flex; padding: 0.35rem 0.75rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 700; letter-spacing: 0.3px; border: 1px solid transparent; }
        .badge-booking { background: #E0F2FE; color: #0284C7; border-color: #BAE6FD; }
        .badge-contact { background: #FEF3C7; color: #D97706; border-color: #FDE68A; }
        
        .empty-state { text-align: center; padding: 4rem 2rem; color: var(--color-text-muted); }
        .empty-icon { font-size: 3rem; color: #CBD5E1; margin-bottom: 1rem; }
        
        .patient-name { font-weight: 700; color: var(--color-primary); display: block; margin-bottom: 0.3rem; font-size: 1rem; }
        .patient-contact { display: flex; align-items: center; gap: 0.5rem; color: var(--color-text-muted); font-size: 0.875rem; margin-bottom: 0.25rem; font-weight: 500; }
        .patient-contact a { color: inherit; text-decoration: none; transition: color 0.2s; }
        .patient-contact a:hover { color: var(--color-accent); }
        .patient-contact i { width: 14px; text-align: center; color: #94A3B8; }
        
        .meta-detail { font-size: 0.9rem; margin-bottom: 0.5rem; line-height: 1.5; color: var(--color-text); }
        .meta-label { font-weight: 600; color: var(--color-primary-light); margin-right: 0.25rem; }
        .message-box { background: #F8FAFC; border: 1px solid #E2E8F0; padding: 1rem 1.25rem; font-size: 0.9rem; color: var(--color-text-muted); border-radius: 8px; margin-top: 0.75rem; line-height: 1.6; }
        
        /* Row Colors - Extremely subtle hover states instead of thick borders */
        tr.row-booking:hover td, tr.row-contact:hover td { background-color: #F1F5F9 !important; }

        /* Action Buttons */
        .action-btns { display: flex; gap: 0.5rem; flex-wrap: wrap; }
        .action-btn { padding: 0.5rem 0.875rem; border: 1px solid transparent; border-radius: 8px; font-size: 0.85rem; font-weight: 600; cursor: pointer; font-family: inherit; transition: all 0.2s; display: inline-flex; align-items: center; gap: 0.4rem; box-shadow: var(--shadow-sm); }
        .btn-approve { background: #ECFDF5; color: #047857; border-color: #A7F3D0; }
        .btn-approve:hover { background: #10B981; color: white; border-color: #10B981; transform: translateY(-1px); box-shadow: var(--shadow); }
        .btn-edit-action { background: #EFF6FF; color: #1D4ED8; border-color: #BFDBFE; }
        .btn-edit-action:hover { background: #3B82F6; color: white; border-color: #3B82F6; transform: translateY(-1px); box-shadow: var(--shadow); }
        .btn-deny { background: #FEF2F2; color: #B91C1C; border-color: #FECACA; }
        .btn-deny:hover { background: #EF4444; color: white; border-color: #EF4444; transform: translateY(-1px); box-shadow: var(--shadow); }
        .action-btn:disabled { opacity: 0.5; cursor: not-allowed; transform: none; box-shadow: none; }

        /* Status Badges within Table */
        .status-badge { display: inline-flex; align-items: center; gap: 0.4rem; padding: 0.4rem 0.875rem; border-radius: 9999px; font-size: 0.8rem; font-weight: 700; border: 1px solid transparent; letter-spacing: 0.3px; }
        .status-approved { background: #ECFDF5; color: #047857; border-color: #D1FAE5; }
        .status-denied { background: #FEF2F2; color: #B91C1C; border-color: #FEE2E2; }
        .status-edited { background: #EFF6FF; color: #1D4ED8; border-color: #DBEAFE; }

        .revised-info { margin-top: 0.75rem; padding: 0.75rem 1rem; background: #F0FDF4; border-radius: 8px; font-size: 0.85rem; border: 1px solid #BBF7D0; color: #166534; font-weight: 500; }
        .revised-info .old-time { text-decoration: line-through; color: #9CA3AF; font-size: 0.8rem; margin-bottom: 0.25rem; display: block; }
        .revised-info .new-time { color: #15803D; font-weight: 700; display: block; }

        /* Modals */
        .modal-overlay { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(4px); z-index: 5000; align-items: center; justify-content: center; }
        .modal-overlay.active { display: flex; animation: fadeIn 0.2s ease; }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        
        .modal-box { background: white; border-radius: 20px; padding: 2.5rem; width: 90%; max-width: 500px; box-shadow: var(--shadow-lg); border: 1px solid rgba(255,255,255,0.2); }
        .modal-box h3 { font-family: var(--font-display); color: var(--color-primary); margin-bottom: 0.75rem; font-size: 1.4rem; font-weight: 700; }
        .modal-box p { color: var(--color-text-muted); font-size: 0.95rem; margin-bottom: 1.5rem; }
        .modal-form-group { margin-bottom: 1.25rem; }
        .modal-form-group label { display: block; font-weight: 600; font-size: 0.85rem; margin-bottom: 0.5rem; color: var(--color-text); }
        .modal-form-group input, .modal-form-group select { width: 100%; padding: 0.75rem 1rem; border: 1.5px solid var(--color-border); border-radius: 10px; font-family: inherit; font-size: 0.95rem; background: #F8FAFC; }
        .modal-form-group input:focus, .modal-form-group select:focus { outline: none; border-color: var(--color-accent); background: white; box-shadow: 0 0 0 4px rgba(5, 150, 105, 0.1); }
        .modal-actions { display: flex; gap: 1rem; margin-top: 2rem; }
        .modal-actions button { flex: 1; padding: 0.875rem; border: none; border-radius: 10px; font-weight: 600; cursor: pointer; font-family: inherit; font-size: 1rem; transition: all 0.2s; box-shadow: var(--shadow-sm); }
        .modal-btn-confirm { background: var(--color-accent); color: white; }
        .modal-btn-confirm:hover { background: var(--color-accent-hover); transform: translateY(-1px); box-shadow: var(--shadow); }
        .modal-btn-cancel { background: white; color: var(--color-text); border: 1.5px solid var(--color-border); }
        .modal-btn-cancel:hover { background: #F1F5F9; border-color: #CBD5E1; }

        /* Blog Editor Specifics */
        .blog-editor-view { display: none; }
        .blog-editor-view.active { display: block; }
        .dashboard-view { display: none; }
        .dashboard-view.active { display: block; }

        .blog-post { background: var(--color-white); border-radius: 16px; overflow: hidden; box-shadow: var(--shadow); display: flex; flex-direction: column; border: 1px solid var(--color-border); transition: transform 0.2s, box-shadow 0.2s; margin-bottom: 2rem;}
        .blog-post:hover { box-shadow: var(--shadow-md); transform: translateY(-2px); }
        @media (min-width: 1024px) { .blog-post { flex-direction: row; } .blog-post.reverse { flex-direction: row-reverse; } }
        .blog-img-wrapper { width: 100%; height: 300px; position: relative; }
        @media (min-width: 1024px) { .blog-img-wrapper { width: 45%; height: auto; min-height: 400px; } }
        .blog-img { width: 100%; height: 100%; object-fit: cover; }
        .blog-content { padding: 3rem; width: 100%; }
        @media (min-width: 1024px) { .blog-content { width: 55%; display: flex; flex-direction: column; justify-content: center; } }
        .blog-meta { display: flex; gap: 1.5rem; color: var(--color-accent); font-size: 0.85rem; margin-bottom: 1.25rem; text-transform: uppercase; letter-spacing: 1px; font-weight: 700; }
        .blog-title { font-size: 2.25rem; color: var(--color-primary); margin-bottom: 1.5rem; line-height: 1.2; font-family: var(--font-display); letter-spacing: -0.5px; }
        .blog-text p { margin-bottom: 1.25rem; color: var(--color-text); line-height: 1.8; font-size: 1.05rem; }
        .blog-text h3 { font-size: 1.5rem; color: var(--color-primary); margin-top: 2rem; margin-bottom: 1rem; font-family: var(--font-display); letter-spacing: -0.3px; }
        .blog-text ul { list-style: disc; padding-left: 2rem; margin-bottom: 1.25rem; color: var(--color-text); line-height: 1.8; font-size: 1.05rem; }
        .blog-text li { margin-bottom: 0.5rem; }

        /* Edit Mode CSS */
        .editor-mode-active [contenteditable="true"] { outline: 2px dashed #93C5FD; padding: 2px 8px; border-radius: 6px; background: rgba(59, 130, 246, 0.05); min-height: 1.5rem; display: inline-block; cursor: text; transition: all 0.2s; }
        .editor-mode-active [contenteditable="true"]:focus { outline: 2px solid #2563EB; background: #fff; box-shadow: var(--shadow-sm); }
        .editor-mode-active div[contenteditable="true"] { display: block; }
        
        .img-upload-overlay { display: none; position: absolute; inset: 0; background: rgba(15, 23, 42, 0.7); backdrop-filter: blur(2px); color: white; justify-content: center; align-items: center; cursor: pointer; font-weight: 600; font-size: 1.1rem; flex-direction: column; gap: 0.75rem; transition: background 0.2s; }
        .editor-mode-active .img-upload-overlay { display: flex; }
        .img-upload-overlay:hover { background: rgba(15, 23, 42, 0.85); }
        
        .btn-toggle-edit { background: var(--color-primary); color: white; padding: 0.875rem 1.5rem; border: none; border-radius: 10px; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 0.5rem; transition: all 0.2s; box-shadow: var(--shadow-sm); font-size: 0.95rem; }
        .btn-toggle-edit:hover { background: var(--color-primary-light); transform: translateY(-1px); box-shadow: var(--shadow); }
        .btn-toggle-edit.active { background: #EF4444; }
        .btn-toggle-edit.active:hover { background: #DC2626; }

        .btn-save-blog { background: var(--color-accent); color: white; padding: 0.875rem 1.5rem; border: none; border-radius: 10px; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 0.5rem; transition: all 0.2s; box-shadow: var(--shadow-sm); font-size: 0.95rem; }
        .btn-save-blog:hover { background: var(--color-accent-hover); transform: translateY(-1px); box-shadow: var(--shadow); }

        .mobile-nav-dropdown { display: none; }

        /* =========================================
           RESPONSIVE OVERRIDES
           ========================================= */
        @media (max-width: 1024px) {
            .sidebar { width: 240px; min-width: 240px; }
            .content-body { padding: 1.5rem; }
        }
        @media (max-width: 768px) {
            .dashboard-layout { flex-direction: column; }
            .sidebar { width: 100%; height: auto; position: sticky; top: 0; z-index: 1000; flex-direction: column; align-items: stretch; background: var(--color-primary); box-shadow: var(--shadow-md); }
            .sidebar-header { padding: 0.875rem 1.25rem; font-size: 1.25rem; border-bottom: 1px solid rgba(255,255,255,0.05); justify-content: center; }
            .sidebar-nav { display: none; }
            
            .mobile-nav-dropdown { display: block; padding: 0.5rem; position: relative; }
            .dropdown-trigger { background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 10px; padding: 0.875rem 1.25rem; display: flex; align-items: center; justify-content: space-between; color: white; font-weight: 600; font-size: 1rem; cursor: pointer; transition: all 0.2s; }
            .dropdown-trigger:active { background: rgba(255,255,255,0.1); }
            .dropdown-trigger i.chevron { transition: transform 0.3s; font-size: 0.8rem; opacity: 0.7; }
            .mobile-nav-dropdown.open .dropdown-trigger i.chevron { transform: rotate(180deg); }
            
            .dropdown-menu { display: none; position: absolute; top: 100%; left: 0.5rem; right: 0.5rem; background: var(--color-primary-light); border-radius: 12px; margin-top: 0.5rem; box-shadow: var(--shadow-lg); overflow: hidden; z-index: 2000; border: 1px solid rgba(255,255,255,0.1); }
            .mobile-nav-dropdown.open .dropdown-menu { display: block; animation: slideDown 0.2s ease-out forwards; }
            @keyframes slideDown { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }
            
            .dropdown-item { display: flex; align-items: center; padding: 1.125rem 1.25rem; color: rgba(255,255,255,0.8); text-decoration: none; font-weight: 500; gap: 1rem; border-bottom: 1px solid rgba(255,255,255,0.05); transition: all 0.2s; }
            .dropdown-item:last-child { border-bottom: none; }
            .dropdown-item i { width: 22px; text-align: center; color: var(--color-accent); font-size: 1.1rem; }
            .dropdown-item:active { background: rgba(255,255,255,0.08); color: white; }
            .dropdown-item.active { background: rgba(5,150,105,0.2); color: white; }
            
            .sidebar-footer { display: none; }
            .main-content { min-height: calc(100vh - 64px); background: #f1f5f9;}
            .topbar { position: relative; top: auto; height: auto; padding: 1rem; flex-direction: column; gap: 0.75rem; text-align: center; background: white; }
            .page-title { font-size: 1.25rem; }
            .date-badge { font-size: 0.8rem; padding: 0.4rem 1rem; }
            .content-body { padding: 1rem; }
            
            .stats-grid { margin-bottom: 1.5rem; gap: 1rem; }
            .stat-card { padding: 1.25rem; }
            .stat-icon { width: 52px; height: 52px; font-size: 1.4rem; margin-right: 1rem; }
            .stat-value { font-size: 2rem; }
            
            /* Responsive Cards instead of Table */
            .table-card { background: transparent; border: none; box-shadow: none; }
            .table-header { padding: 1.25rem; background: white; border-radius: 12px 12px 0 0; border: 1px solid var(--color-border); border-bottom: none; }
            table, thead, tbody, th, td, tr { display: block; }
            thead tr { position: absolute; top: -9999px; left: -9999px; }
            tr { border: 1px solid var(--color-border); border-radius: 12px; margin-bottom: 1rem; position: relative; overflow: hidden; background: white; box-shadow: var(--shadow-sm); }
            td { border: none; border-bottom: 1px solid var(--color-border); position: relative; padding-left: 35% !important; min-height: 4rem; padding-top: 1.25rem; padding-bottom: 1.25rem; font-size: 0.9rem; }
            td:last-child { border-bottom: 0; padding-bottom: 1.5rem; }
            td:before { position: absolute; top: 1.25rem; left: 1rem; width: 30%; font-weight: 600; color: var(--color-text-muted); font-size: 0.75rem; text-transform: uppercase; white-space: nowrap; }
            td:nth-of-type(1):before { content: "Date & Type"; }
            td:nth-of-type(2):before { content: "Patient Info"; }
            td:nth-of-type(3):before { content: "Details"; }
            td:nth-of-type(4):before { content: "Actions"; margin-top: 0.25rem; }
            .action-btns { flex-direction: column; gap: 0.5rem; width: 100%; }
            .action-btn { width: 100%; justify-content: center; padding: 0.75rem; }
            
            .login-card { padding: 2.5rem 2rem; border-radius: 20px; min-height: unset; margin: 1.5rem; display: flex; flex-direction: column; justify-content: center; background: rgba(255,255,255,0.95); border: 1px solid rgba(255,255,255,0.2); }
        }
    </style>
</head>
<body>

<?php if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true): ?>
    <div class="login-wrapper">
        <div class="login-card">
            <div class="login-logo">
                <img src="images/favicon.png" alt="Mijon logo"> Mijon <span>Clinic</span>
            </div>
            
            <?php if (isset($error)): ?>
                <div class="error-msg">
                    <i class="fa-solid fa-triangle-exclamation"></i> <?php echo $error; ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="admin.php">
                <div class="form-group">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" class="form-control" required autofocus placeholder="Admin ID">
                </div>
                <div class="form-group">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required placeholder="••••••••">
                </div>
                <button type="submit" name="login" class="btn-submit">Sign In to Dashboard</button>
            </form>
        </div>
    </div>
<?php else: ?>
    <div class="dashboard-layout">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <img src="images/favicon.png" alt="Mijon logo"> Mijon <span>Admin</span>
            </div>

            <!-- Mobile Dropdown Navigation -->
            <div class="mobile-nav-dropdown" id="mobile-nav-container">
                <div class="dropdown-trigger" onclick="toggleMobileNav()">
                    <span><i class="fa-regular fa-calendar-check" style="margin-right: 0.5rem;"></i> <span id="mobile-current-tab">Bookings</span></span>
                    <i class="fa-solid fa-chevron-down chevron"></i>
                </div>
                <div class="dropdown-menu">
                    <a href="#" class="dropdown-item active" onclick="handleMobileNav(event, 'nav-bookings', 'view-bookings', 'Bookings', '<i class=\'fa-regular fa-calendar-check\'></i> Bookings')">
                        <i class="fa-regular fa-calendar-check"></i> Bookings
                    </a>
                    <a href="#" class="dropdown-item" onclick="handleMobileNav(event, 'nav-contacts', 'view-contacts', 'Contacts', '<i class=\'fa-regular fa-envelope\'></i> Contacts')">
                        <i class="fa-regular fa-envelope"></i> Contacts
                    </a>
                    <a href="#" class="dropdown-item" onclick="handleMobileNav(event, 'nav-chatleads', 'view-chatleads', 'Chat Leads', '<i class=\'fa-solid fa-comments\'></i> Chat Leads')">
                        <i class="fa-solid fa-comments"></i> Chat Leads
                    </a>
                    <a href="#" class="dropdown-item" onclick="handleMobileNav(event, 'nav-blog', 'view-blog', 'Blog Editor', '<i class=\'fa-solid fa-pen-nib\'></i> Blog Editor')">
                        <i class="fa-solid fa-pen-nib"></i> Edit Blog
                    </a>
                    <a href="index.html" target="_blank" class="dropdown-item" style="border-top: 1px solid rgba(255,255,255,0.1); color: var(--color-accent);">
                        <i class="fa-solid fa-globe"></i> View Live Site
                    </a>
                    <a href="admin.php?logout=1" class="dropdown-item" style="color: #F87171;">
                        <i class="fa-solid fa-right-from-bracket"></i> Logout
                    </a>
                </div>
            </div>

            <nav class="sidebar-nav">
                <a href="#" class="nav-item active" id="nav-bookings">
                    <i class="fa-regular fa-calendar-check"></i> Bookings
                </a>
                <a href="#" class="nav-item" id="nav-contacts">
                    <i class="fa-regular fa-envelope"></i> Contacts
                </a>
                <a href="#" class="nav-item" id="nav-chatleads">
                    <i class="fa-solid fa-comments"></i> Chat Leads
                </a>
                <a href="#" class="nav-item" id="nav-blog">
                    <i class="fa-solid fa-pen-nib"></i> Edit Blog
                </a>
                <a href="index.html" target="_blank" class="nav-item" id="nav-livesite">
                    <i class="fa-solid fa-globe"></i> View Live Site
                </a>
            </nav>
            <div class="sidebar-footer">
                <a href="admin.php?logout=1" class="btn-logout">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i> Sign Out
                </a>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Topbar -->
            <header class="topbar">
                <h1 class="page-title">Booking Requests</h1>
                <div class="date-badge">
                    <i class="fa-solid fa-circle-dot" style="color: var(--color-accent); font-size: 0.6rem; animation: pulse 2s infinite;"></i>
                    <span style="margin-left: 0.5rem; margin-right: 1rem;">Live Updates On</span>
                    <i class="fa-regular fa-calendar" style="margin-right: 0.5rem;"></i>
                    <?php echo date('l, F j, Y'); ?>
                </div>
            </header>

                <style>
        :root {
            /* Premium Color Palette */
            --color-primary: #0F172A; /* Deep Slate */
            --color-primary-light: #1E293B;
            --color-accent: #059669; /* Emerald */
            --color-accent-hover: #047857;
            --color-gold: #D4AF37; /* Clinical Gold */
            --color-bg: #F8FAFC; /* Soft cool gray for background */
            --color-surface: #FFFFFF;
            --color-border: #E2E8F0;
            --color-text: #334155;
            --color-text-muted: #64748B;
            
            /* Typography */
            --font-sans: 'Inter', system-ui, -apple-system, sans-serif;
            --font-display: 'Outfit', system-ui, sans-serif;
            
            /* Premium Soft Shadows */
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
            --shadow-md: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.02);
            --shadow-lg: 0 20px 25px -5px rgba(0, 0, 0, 0.05), 0 10px 10px -5px rgba(0, 0, 0, 0.02);
            --shadow-glass: 0 8px 32px 0 rgba(31, 38, 135, 0.15);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: var(--font-sans); background-color: var(--color-bg); color: var(--color-text); line-height: 1.6; -webkit-font-smoothing: antialiased; }

        /* =========================================
           LOGIN SCREEN 
           ========================================= */
        .login-wrapper { display: flex; align-items: center; justify-content: center; min-height: 100vh; background: linear-gradient(135deg, #0F172A 0%, #1E293B 100%); position: relative; overflow: hidden; }
        .login-wrapper::before { content: ''; position: absolute; width: 400px; height: 400px; background: rgba(5, 150, 105, 0.15); border-radius: 50%; top: -100px; left: -100px; filter: blur(60px); }
        .login-wrapper::after { content: ''; position: absolute; width: 300px; height: 300px; background: rgba(212, 175, 55, 0.15); border-radius: 50%; bottom: -50px; right: -50px; filter: blur(60px); }
        
        .login-card { 
            background: rgba(255, 255, 255, 0.95); 
            backdrop-filter: blur(10px);
            padding: 3.5rem 3rem; 
            border-radius: 20px; 
            box-shadow: var(--shadow-glass); 
            border: 1px solid rgba(255, 255, 255, 0.2);
            width: 100%; 
            max-width: 420px; 
            position: relative;
            z-index: 10;
        }
        .login-logo { text-align: center; font-family: var(--font-display); font-size: 2rem; color: var(--color-primary); margin-bottom: 2.5rem; font-weight: 700; letter-spacing: -0.5px; }
        .login-logo span { color: var(--color-accent); }
        .login-logo img { width: 32px; height: 32px; object-fit: contain; margin-right: 0.5rem; vertical-align: bottom; }
        .form-group { margin-bottom: 1.5rem; }
        .form-label { display: block; margin-bottom: 0.5rem; font-weight: 600; font-size: 0.85rem; color: var(--color-text); text-transform: uppercase; letter-spacing: 0.5px; }
        .form-control { width: 100%; padding: 0.85rem 1.125rem; border: 1.5px solid var(--color-border); border-radius: 10px; font-family: inherit; font-size: 1rem; transition: all 0.2s; outline: none; background: #F8FAFC; color: var(--color-text); }
        .form-control:focus { border-color: var(--color-accent); background: white; box-shadow: 0 0 0 4px rgba(5, 150, 105, 0.1); }
        .btn-submit { display: block; width: 100%; padding: 0.875rem; background: var(--color-primary); color: white; border: none; border-radius: 10px; font-weight: 600; font-family: inherit; font-size: 1.05rem; cursor: pointer; transition: all 0.2s ease; margin-top: 2rem; box-shadow: 0 4px 6px rgba(15, 23, 42, 0.2); }
        .btn-submit:hover { background: var(--color-primary-light); transform: translateY(-1px); box-shadow: 0 6px 12px rgba(15, 23, 42, 0.25); }
        .error-msg { background: #FEF2F2; color: #DC2626; padding: 1rem; border-radius: 8px; font-size: 0.9rem; margin-bottom: 1.5rem; border: 1px solid #F87171; text-align: center; font-weight: 500; }

        /* =========================================
           DASHBOARD LAYOUT 
           ========================================= */
        .dashboard-layout { display: flex; min-height: 100vh; }
        
        /* Sidebar */
        .sidebar { width: 280px; min-width: 280px; flex-shrink: 0; background: var(--color-primary); color: white; display: flex; flex-direction: column; position: sticky; top: 0; height: 100vh; box-shadow: var(--shadow-md); z-index: 50; }
        .sidebar-header { padding: 1.75rem 1.5rem; font-family: var(--font-display); font-size: 1.5rem; font-weight: 700; color: white; border-bottom: 1px solid rgba(255,255,255,0.05); }
        .sidebar-header span { color: var(--color-accent); }
        .sidebar-header img { width: 26px; height: 26px; object-fit: contain; margin-right: 0.5rem; vertical-align: middle; }
        .sidebar-nav { flex-grow: 1; padding: 1.5rem 0; display: flex; flex-direction: column; gap: 0.25rem; }
        .nav-item { display: flex; align-items: center; padding: 0.875rem 1.5rem; margin: 0 1rem; color: rgba(255,255,255,0.7); text-decoration: none; transition: all 0.2s; font-weight: 500; border-radius: 8px; box-sizing: border-box; }
        .nav-item i { width: 24px; font-size: 1.25rem; margin-right: 0.75rem; text-align: center; transition: color 0.2s; }
        .nav-item:hover { background: rgba(255,255,255,0.05); color: white; }
        .nav-item.active { background: rgba(5, 150, 105, 0.15); color: white; color: var(--color-accent); }
        .nav-item.active i { color: var(--color-accent); }
        
        .sidebar-footer { padding: 1.5rem; border-top: 1px solid rgba(255,255,255,0.05); }
        .btn-logout { display: flex; align-items: center; justify-content: center; width: 100%; padding: 0.875rem; background: rgba(255,255,255,0.05); color: white; text-decoration: none; border-radius: 8px; font-weight: 600; transition: all 0.2s; border: 1px solid rgba(255,255,255,0.1); }
        .btn-logout i { margin-right: 0.5rem; }
        .btn-logout:hover { background: #DC2626; border-color: #DC2626; transform: translateY(-1px); }

        /* Main Content */
        .main-content { flex-grow: 1; min-width: 0; display: flex; flex-direction: column; overflow: hidden; background: var(--color-bg); }
        .topbar { background: var(--color-surface); height: 80px; border-bottom: 1px solid var(--color-border); display: flex; align-items: center; justify-content: space-between; padding: 0 2.5rem; z-index: 40; }
        .page-title { font-family: var(--font-display); font-size: 1.4rem; font-weight: 700; color: var(--color-primary); letter-spacing: -0.5px; }
        
        /* Current Date Badge */
        .date-badge { background: #F1F5F9; padding: 0.6rem 1.25rem; border-radius: 9999px; font-size: 0.875rem; color: var(--color-text-muted); font-weight: 600; display: flex; align-items: center; border: 1px solid #E2E8F0; }

        /* Dashboard Body */
        .content-body { padding: 2.5rem; overflow-y: auto; }
        
        /* Stats row */
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem; margin-bottom: 2.5rem; }
        .stat-card { background: var(--color-surface); padding: 1.75rem; border-radius: 16px; box-shadow: var(--shadow); border: 1px solid var(--color-border); display: flex; align-items: center; transition: transform 0.2s, box-shadow 0.2s; position: relative; overflow: hidden; }
        .stat-card:hover { transform: translateY(-2px); box-shadow: var(--shadow-md); }
        /* Add subtle gleam to stats */
        .stat-card::after { content:''; position:absolute; top:0; left:0; right:0; height:4px; opacity:0.8; }
        .stat-card:nth-child(1)::after { background: var(--color-accent); }
        .stat-card:nth-child(2)::after { background: var(--color-gold); }
        .stat-card:nth-child(3)::after { background: #3B82F6; }

        .stat-icon { width: 64px; height: 64px; border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 1.75rem; margin-right: 1.25rem; box-shadow: var(--shadow-sm); }
        .icon-primary { background: linear-gradient(135deg, #10B981, #059669); color: white; }
        .icon-accent { background: linear-gradient(135deg, #FCD34D, #D4AF37); color: white; }
        .icon-blue { background: linear-gradient(135deg, #60A5FA, #3B82F6); color: white; } /* Reused chat-lead */
        
        .stat-label { color: var(--color-text-muted); font-size: 0.9rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.25rem; }
        .stat-value { font-family: var(--font-display); font-size: 2.5rem; font-weight: 700; color: var(--color-primary); line-height: 1; letter-spacing: -1px; }

        /* Table Card (Data Grid) */
        .table-card { background: var(--color-surface); border-radius: 16px; box-shadow: var(--shadow); border: 1px solid var(--color-border); overflow: hidden; margin-bottom: 2rem; }
        .table-header { padding: 1.5rem 1.75rem; border-bottom: 1px solid var(--color-border); display: flex; justify-content: space-between; align-items: center; background: rgba(248, 250, 252, 0.5); }
        .table-title { font-weight: 700; font-size: 1.15rem; color: var(--color-primary); display: flex; align-items: center; font-family: var(--font-display); letter-spacing: -0.3px; }
        .table-title i { margin-right: 0.75rem; color: var(--color-accent); font-size: 1.2rem; }
        
        table { width: 100%; border-collapse: separate; border-spacing: 0; text-align: left; }
        th { padding: 1rem 1.75rem; background: #F8FAFC; font-weight: 600; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.05em; color: var(--color-text-muted); border-bottom: 1px solid var(--color-border); }
        td { padding: 1.25rem 1.75rem; border-bottom: 1px solid var(--color-border); font-size: 0.95rem; vertical-align: top; color: var(--color-text); transition: background 0.15s; }
        tr:last-child td { border-bottom: none; }
        
        .badge { display: inline-flex; padding: 0.35rem 0.75rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 700; letter-spacing: 0.3px; border: 1px solid transparent; }
        .badge-booking { background: #E0F2FE; color: #0284C7; border-color: #BAE6FD; }
        .badge-contact { background: #FEF3C7; color: #D97706; border-color: #FDE68A; }
        
        .empty-state { text-align: center; padding: 4rem 2rem; color: var(--color-text-muted); }
        .empty-icon { font-size: 3rem; color: #CBD5E1; margin-bottom: 1rem; }
        
        .patient-name { font-weight: 700; color: var(--color-primary); display: block; margin-bottom: 0.3rem; font-size: 1rem; }
        .patient-contact { display: flex; align-items: center; gap: 0.5rem; color: var(--color-text-muted); font-size: 0.875rem; margin-bottom: 0.25rem; font-weight: 500; }
        .patient-contact a { color: inherit; text-decoration: none; transition: color 0.2s; }
        .patient-contact a:hover { color: var(--color-accent); }
        .patient-contact i { width: 14px; text-align: center; color: #94A3B8; }
        
        .meta-detail { font-size: 0.9rem; margin-bottom: 0.5rem; line-height: 1.5; color: var(--color-text); }
        .meta-label { font-weight: 600; color: var(--color-primary-light); margin-right: 0.25rem; }
        .message-box { background: #F8FAFC; border: 1px solid #E2E8F0; padding: 1rem 1.25rem; font-size: 0.9rem; color: var(--color-text-muted); border-radius: 8px; margin-top: 0.75rem; line-height: 1.6; }
        
        /* Row Colors - Extremely subtle hover states instead of thick borders */
        tr.row-booking:hover td, tr.row-contact:hover td { background-color: #F1F5F9 !important; }

        /* Action Buttons */
        .action-btns { display: flex; gap: 0.5rem; flex-wrap: wrap; }
        .action-btn { padding: 0.5rem 0.875rem; border: 1px solid transparent; border-radius: 8px; font-size: 0.85rem; font-weight: 600; cursor: pointer; font-family: inherit; transition: all 0.2s; display: inline-flex; align-items: center; gap: 0.4rem; box-shadow: var(--shadow-sm); }
        .btn-approve { background: #ECFDF5; color: #047857; border-color: #A7F3D0; }
        .btn-approve:hover { background: #10B981; color: white; border-color: #10B981; transform: translateY(-1px); box-shadow: var(--shadow); }
        .btn-edit-action { background: #EFF6FF; color: #1D4ED8; border-color: #BFDBFE; }
        .btn-edit-action:hover { background: #3B82F6; color: white; border-color: #3B82F6; transform: translateY(-1px); box-shadow: var(--shadow); }
        .btn-deny { background: #FEF2F2; color: #B91C1C; border-color: #FECACA; }
        .btn-deny:hover { background: #EF4444; color: white; border-color: #EF4444; transform: translateY(-1px); box-shadow: var(--shadow); }
        .action-btn:disabled { opacity: 0.5; cursor: not-allowed; transform: none; box-shadow: none; }

        /* Status Badges within Table */
        .status-badge { display: inline-flex; align-items: center; gap: 0.4rem; padding: 0.4rem 0.875rem; border-radius: 9999px; font-size: 0.8rem; font-weight: 700; border: 1px solid transparent; letter-spacing: 0.3px; }
        .status-approved { background: #ECFDF5; color: #047857; border-color: #D1FAE5; }
        .status-denied { background: #FEF2F2; color: #B91C1C; border-color: #FEE2E2; }
        .status-edited { background: #EFF6FF; color: #1D4ED8; border-color: #DBEAFE; }

        .revised-info { margin-top: 0.75rem; padding: 0.75rem 1rem; background: #F0FDF4; border-radius: 8px; font-size: 0.85rem; border: 1px solid #BBF7D0; color: #166534; font-weight: 500; }
        .revised-info .old-time { text-decoration: line-through; color: #9CA3AF; font-size: 0.8rem; margin-bottom: 0.25rem; display: block; }
        .revised-info .new-time { color: #15803D; font-weight: 700; display: block; }

        /* Modals */
        .modal-overlay { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(4px); z-index: 5000; align-items: center; justify-content: center; }
        .modal-overlay.active { display: flex; animation: fadeIn 0.2s ease; }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        
        .modal-box { background: white; border-radius: 20px; padding: 2.5rem; width: 90%; max-width: 500px; box-shadow: var(--shadow-lg); border: 1px solid rgba(255,255,255,0.2); }
        .modal-box h3 { font-family: var(--font-display); color: var(--color-primary); margin-bottom: 0.75rem; font-size: 1.4rem; font-weight: 700; }
        .modal-box p { color: var(--color-text-muted); font-size: 0.95rem; margin-bottom: 1.5rem; }
        .modal-form-group { margin-bottom: 1.25rem; }
        .modal-form-group label { display: block; font-weight: 600; font-size: 0.85rem; margin-bottom: 0.5rem; color: var(--color-text); }
        .modal-form-group input, .modal-form-group select { width: 100%; padding: 0.75rem 1rem; border: 1.5px solid var(--color-border); border-radius: 10px; font-family: inherit; font-size: 0.95rem; background: #F8FAFC; }
        .modal-form-group input:focus, .modal-form-group select:focus { outline: none; border-color: var(--color-accent); background: white; box-shadow: 0 0 0 4px rgba(5, 150, 105, 0.1); }
        .modal-actions { display: flex; gap: 1rem; margin-top: 2rem; }
        .modal-actions button { flex: 1; padding: 0.875rem; border: none; border-radius: 10px; font-weight: 600; cursor: pointer; font-family: inherit; font-size: 1rem; transition: all 0.2s; box-shadow: var(--shadow-sm); }
        .modal-btn-confirm { background: var(--color-accent); color: white; }
        .modal-btn-confirm:hover { background: var(--color-accent-hover); transform: translateY(-1px); box-shadow: var(--shadow); }
        .modal-btn-cancel { background: white; color: var(--color-text); border: 1.5px solid var(--color-border); }
        .modal-btn-cancel:hover { background: #F1F5F9; border-color: #CBD5E1; }

        /* Blog Editor Specifics */
        .blog-editor-view { display: none; }
        .blog-editor-view.active { display: block; }
        .dashboard-view { display: none; }
        .dashboard-view.active { display: block; }

        .blog-post { background: var(--color-white); border-radius: 16px; overflow: hidden; box-shadow: var(--shadow); display: flex; flex-direction: column; border: 1px solid var(--color-border); transition: transform 0.2s, box-shadow 0.2s; margin-bottom: 2rem;}
        .blog-post:hover { box-shadow: var(--shadow-md); transform: translateY(-2px); }
        @media (min-width: 1024px) { .blog-post { flex-direction: row; } .blog-post.reverse { flex-direction: row-reverse; } }
        .blog-img-wrapper { width: 100%; height: 300px; position: relative; display: flex; align-items: center; justify-content: center; background: #000; overflow: hidden; }
        @media (min-width: 1024px) { .blog-img-wrapper { width: 45%; height: auto; min-height: 400px; } }
        .blog-img-wrapper iframe { width: 100%; height: 100%; border: none; min-height: 400px; display: block; }
        /* Optimization for portrait videos (TikTok) */
        .blog-img-wrapper iframe[src*="tiktok.com"] { min-height: 580px; }
        .blog-img { width: 100%; height: 100%; object-fit: cover; }
        .blog-content { padding: 3rem; width: 100%; }
        @media (min-width: 1024px) { .blog-content { width: 55%; display: flex; flex-direction: column; justify-content: center; } }
        .blog-meta { display: flex; gap: 1.5rem; color: var(--color-accent); font-size: 0.85rem; margin-bottom: 1.25rem; text-transform: uppercase; letter-spacing: 1px; font-weight: 700; }
        .blog-title { font-size: 2.25rem; color: var(--color-primary); margin-bottom: 1.5rem; line-height: 1.2; font-family: var(--font-display); letter-spacing: -0.5px; }
        .blog-text p { margin-bottom: 1.25rem; color: var(--color-text); line-height: 1.8; font-size: 1.05rem; }
        .blog-text h3 { font-size: 1.5rem; color: var(--color-primary); margin-top: 2rem; margin-bottom: 1rem; font-family: var(--font-display); letter-spacing: -0.3px; }
        .blog-text ul { list-style: disc; padding-left: 2rem; margin-bottom: 1.25rem; color: var(--color-text); line-height: 1.8; font-size: 1.05rem; }
        .blog-text li { margin-bottom: 0.5rem; }

        /* Edit Mode CSS */
        .editor-mode-active [contenteditable="true"] { outline: 2px dashed #93C5FD; padding: 2px 8px; border-radius: 6px; background: rgba(59, 130, 246, 0.05); min-height: 1.5rem; display: inline-block; cursor: text; transition: all 0.2s; }
        .editor-mode-active [contenteditable="true"]:focus { outline: 2px solid #2563EB; background: #fff; box-shadow: var(--shadow-sm); }
        .editor-mode-active div[contenteditable="true"] { display: block; }
        
        .img-upload-overlay { display: none; position: absolute; inset: 0; background: rgba(15, 23, 42, 0.7); backdrop-filter: blur(2px); color: white; justify-content: center; align-items: center; cursor: pointer; font-weight: 600; font-size: 1.1rem; flex-direction: column; gap: 0.75rem; transition: background 0.2s; }
        .editor-mode-active .img-upload-overlay { display: flex; }
        .img-upload-overlay:hover { background: rgba(15, 23, 42, 0.85); }
        
        .btn-toggle-edit { background: var(--color-primary); color: white; padding: 0.875rem 1.5rem; border: none; border-radius: 10px; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 0.5rem; transition: all 0.2s; box-shadow: var(--shadow-sm); font-size: 0.95rem; }
        .btn-toggle-edit:hover { background: var(--color-primary-light); transform: translateY(-1px); box-shadow: var(--shadow); }
        .btn-toggle-edit.active { background: #EF4444; }
        .btn-toggle-edit.active:hover { background: #DC2626; }

        .btn-save-blog { background: var(--color-accent); color: white; padding: 0.875rem 1.5rem; border: none; border-radius: 10px; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 0.5rem; transition: all 0.2s; box-shadow: var(--shadow-sm); font-size: 0.95rem; }
        .btn-save-blog:hover { background: var(--color-accent-hover); transform: translateY(-1px); box-shadow: var(--shadow); }

        .mobile-nav-dropdown { display: none; }

        /* =========================================
           RESPONSIVE OVERRIDES
           ========================================= */
        @media (max-width: 1024px) {
            .sidebar { width: 240px; min-width: 240px; }
            .content-body { padding: 1.5rem; }
        }
        @media (max-width: 768px) {
            .dashboard-layout { flex-direction: column; }
            .sidebar { width: 100%; height: auto; position: sticky; top: 0; z-index: 1000; flex-direction: column; align-items: stretch; background: var(--color-primary); box-shadow: var(--shadow-md); }
            .sidebar-header { padding: 0.875rem 1.25rem; font-size: 1.25rem; border-bottom: 1px solid rgba(255,255,255,0.05); justify-content: center; }
            .sidebar-nav { display: none; }
            
            .mobile-nav-dropdown { display: block; padding: 0.5rem; position: relative; }
            .dropdown-trigger { background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 10px; padding: 0.875rem 1.25rem; display: flex; align-items: center; justify-content: space-between; color: white; font-weight: 600; font-size: 1rem; cursor: pointer; transition: all 0.2s; }
            .dropdown-trigger:active { background: rgba(255,255,255,0.1); }
            .dropdown-trigger i.chevron { transition: transform 0.3s; font-size: 0.8rem; opacity: 0.7; }
            .mobile-nav-dropdown.open .dropdown-trigger i.chevron { transform: rotate(180deg); }
            
            .dropdown-menu { display: none; position: absolute; top: 100%; left: 0.5rem; right: 0.5rem; background: var(--color-primary-light); border-radius: 12px; margin-top: 0.5rem; box-shadow: var(--shadow-lg); overflow: hidden; z-index: 2000; border: 1px solid rgba(255,255,255,0.1); }
            .mobile-nav-dropdown.open .dropdown-menu { display: block; animation: slideDown 0.2s ease-out forwards; }
            @keyframes slideDown { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }
            
            .dropdown-item { display: flex; align-items: center; padding: 1.125rem 1.25rem; color: rgba(255,255,255,0.8); text-decoration: none; font-weight: 500; gap: 1rem; border-bottom: 1px solid rgba(255,255,255,0.05); transition: all 0.2s; }
            .dropdown-item:last-child { border-bottom: none; }
            .dropdown-item i { width: 22px; text-align: center; color: var(--color-accent); font-size: 1.1rem; }
            .dropdown-item:active { background: rgba(255,255,255,0.08); color: white; }
            .dropdown-item.active { background: rgba(5,150,105,0.2); color: white; }
            
            .sidebar-footer { display: none; }
            .main-content { min-height: calc(100vh - 64px); background: #f1f5f9;}
            .topbar { position: relative; top: auto; height: auto; padding: 1rem; flex-direction: column; gap: 0.75rem; text-align: center; background: white; }
            .page-title { font-size: 1.25rem; }
            .date-badge { font-size: 0.8rem; padding: 0.4rem 1rem; }
            .content-body { padding: 1rem; }
            
            .stats-grid { margin-bottom: 1.5rem; gap: 1rem; }
            .stat-card { padding: 1.25rem; }
            .stat-icon { width: 52px; height: 52px; font-size: 1.4rem; margin-right: 1rem; }
            .stat-value { font-size: 2rem; }
            
            /* Responsive Cards instead of Table */
            .table-card { background: transparent; border: none; box-shadow: none; }
            .table-header { padding: 1.25rem; background: white; border-radius: 12px 12px 0 0; border: 1px solid var(--color-border); border-bottom: none; }
            table, thead, tbody, th, td, tr { display: block; }
            thead tr { position: absolute; top: -9999px; left: -9999px; }
            tr { border: 1px solid var(--color-border); border-radius: 12px; margin-bottom: 1rem; position: relative; overflow: hidden; background: white; box-shadow: var(--shadow-sm); }
            td { border: none; border-bottom: 1px solid var(--color-border); position: relative; padding-left: 35% !important; min-height: 4rem; padding-top: 1.25rem; padding-bottom: 1.25rem; font-size: 0.9rem; }
            td:last-child { border-bottom: 0; padding-bottom: 1.5rem; }
            td:before { position: absolute; top: 1.25rem; left: 1rem; width: 30%; font-weight: 600; color: var(--color-text-muted); font-size: 0.75rem; text-transform: uppercase; white-space: nowrap; }
            td:nth-of-type(1):before { content: "Date & Type"; }
            td:nth-of-type(2):before { content: "Patient Info"; }
            td:nth-of-type(3):before { content: "Details"; }
            td:nth-of-type(4):before { content: "Actions"; margin-top: 0.25rem; }
            .action-btns { flex-direction: column; gap: 0.5rem; width: 100%; }
            .action-btn { width: 100%; justify-content: center; padding: 0.75rem; }
            
            .login-card { padding: 2.5rem 2rem; border-radius: 20px; min-height: unset; margin: 1.5rem; display: flex; flex-direction: column; justify-content: center; background: rgba(255,255,255,0.95); border: 1px solid rgba(255,255,255,0.2); }
        }
    </style>

            <!-- Content -->
            <div class="content-body">
                
                <!-- VIEW 1: Bookings -->
                <div id="view-bookings" class="dashboard-view active">
                    <div class="stats-grid">
                        <div class="stat-card">
                            <div class="stat-icon icon-primary"><i class="fa-regular fa-calendar-check"></i></div>
                            <div>
                                <div class="stat-label">Total Booking Requests</div>
                                <div class="stat-value" id="total-bookings-count">0</div>
                            </div>
                        </div>
                    </div>
                    <div class="table-card">
                        <div class="table-header">
                            <div class="table-title"><i class="fa-solid fa-calendar-days"></i> Booking Requests</div>
                        </div>
                        <div style="overflow-x: auto;">
                            <table>
                                <thead>
                                    <tr>
                                        <th style="width: 13%;">Date</th>
                                        <th style="width: 20%;">Patient Info</th>
                                        <th style="width: 42%;">Request Details</th>
                                        <th style="width: 25%;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="bookings-table-body">
                                    <tr>
                                        <td colspan="4" style="text-align: center; padding: 3rem;">
                                            <i class="fa-solid fa-circle-notch fa-spin" style="font-size: 2rem; color: var(--color-border); margin-bottom: 1rem;"></i>
                                            <p style="color: var(--color-text-muted);">Loading bookings...</p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div> <!-- END Bookings View -->

                <!-- VIEW 2: Contacts -->
                <div id="view-contacts" class="dashboard-view">
                    <div class="stats-grid">
                        <div class="stat-card">
                            <div class="stat-icon icon-accent"><i class="fa-regular fa-envelope"></i></div>
                            <div>
                                <div class="stat-label">General Inquiries</div>
                                <div class="stat-value" id="total-contacts-count">0</div>
                            </div>
                        </div>
                    </div>
                    <div class="table-card">
                        <div class="table-header">
                            <div class="table-title"><i class="fa-solid fa-envelope-open-text"></i> Contact Messages</div>
                        </div>
                        <div style="overflow-x: auto;">
                            <table>
                                <thead>
                                    <tr>
                                        <th style="width: 13%;">Date</th>
                                        <th style="width: 20%;">Contact Info</th>
                                        <th style="width: 67%;">Message</th>
                                    </tr>
                                </thead>
                                <tbody id="contacts-table-body">
                                    <tr>
                                        <td colspan="3" style="text-align: center; padding: 3rem;">
                                            <i class="fa-solid fa-circle-notch fa-spin" style="font-size: 2rem; color: var(--color-border); margin-bottom: 1rem;"></i>
                                            <p style="color: var(--color-text-muted);">Loading contacts...</p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div> <!-- END Contacts View -->

                <!-- VIEW 3: Chat Leads -->
                <div id="view-chatleads" class="dashboard-view">
                    <div class="stats-grid">
                        <div class="stat-card">
                            <div class="stat-icon" style="background: rgba(212,175,55,0.1); color: #D4AF37;"><i class="fa-solid fa-comments"></i></div>
                            <div>
                                <div class="stat-label">Chat Leads</div>
                                <div class="stat-value" id="total-chatleads-count">0</div>
                            </div>
                        </div>
                    </div>
                    <div class="table-card">
                        <div class="table-header">
                            <div class="table-title"><i class="fa-solid fa-message"></i> Chat Lead Inquiries</div>
                        </div>
                        <div style="overflow-x: auto;">
                            <table>
                                <thead>
                                    <tr>
                                        <th style="width: 13%;">Date</th>
                                        <th style="width: 22%;">Client Info</th>
                                        <th style="width: 65%;">Details</th>
                                    </tr>
                                </thead>
                                <tbody id="chatleads-table-body">
                                    <tr>
                                        <td colspan="3" style="text-align: center; padding: 3rem;">
                                            <i class="fa-solid fa-circle-notch fa-spin" style="font-size: 2rem; color: var(--color-border); margin-bottom: 1rem;"></i>
                                            <p style="color: var(--color-text-muted);">Loading chat leads...</p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div> <!-- END Chat Leads View -->

                <!-- VIEW 3: Blog Editor -->
                <div id="view-blog" class="blog-editor-view">
                    
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
                        <div>
                            <h2 style="font-family: var(--font-display); color: var(--color-primary); margin-bottom: 0.25rem;">Visual Blog Editor</h2>
                            <p style="color: var(--color-text-muted); font-size: 0.9rem;">What you see here is exactly how your public blog looks.</p>
                        </div>
                        <div style="display: flex; gap: 1rem;">
                            <button class="btn-toggle-edit" onclick="addNewBlog()" id="btn-add-blog" style="background: #10B981; border: none;">
                                <i class="fa-solid fa-plus"></i> Add Blog
                            </button>
                            <button class="btn-toggle-edit" onclick="toggleEditMode()" id="btn-toggle-edit">
                                <i class="fa-solid fa-pen-to-square"></i> Enable Editing
                            </button>
                            <button class="btn-save-blog" onclick="saveAllBlogs()" id="global-save-btn">
                                <i class="fa-solid fa-cloud-arrow-up"></i> Save All Changes
                            </button>
                        </div>
                    </div>

                    <!-- Blog items will be generated here by JS -->
                    <div id="blog-editor-container">
                        <div style="text-align: center; padding: 3rem;">
                            <i class="fa-solid fa-circle-notch fa-spin" style="font-size: 2rem; color: var(--color-border); margin-bottom: 1rem;"></i>
                            <p style="color: var(--color-text-muted);">Loading blog data...</p>
                        </div>
                    </div>

                </div> <!-- END Blog Editor View -->
                
            </div>
        </main>
    </div>

    <!-- Edit Modal -->
    <div class="modal-overlay" id="editModal">
        <div class="modal-box">
            <h3><i class="fa-solid fa-calendar-pen" style="margin-right: 0.5rem;"></i> Reschedule Appointment</h3>
            <p>Update the date and time, then approve the revised appointment.</p>
            <input type="hidden" id="editLineIndex">
            <div id="editPatientInfo" style="background: #F9FAFB; padding: 0.75rem 1rem; border-radius: 8px; margin-bottom: 1.25rem; font-size: 0.9rem; color: var(--color-text);"></div>
            <div class="modal-form-group">
                <label>Revised Date</label>
                <input type="date" id="editDate">
            </div>
            <div class="modal-form-group">
                <label>Revised Time</label>
                <select id="editTime">
                    <option value="Morning">Morning (10:00 AM - 1:00 PM)</option>
                    <option value="Afternoon">Afternoon (1:00 PM - 4:00 PM)</option>
                    <option value="Evening">Late Afternoon (4:00 PM - 6:00 PM)</option>
                </select>
            </div>
            <div class="modal-actions">
                <button class="modal-btn-cancel" onclick="closeEditModal()">Cancel</button>
                <button class="modal-btn-confirm" onclick="confirmEdit()"><i class="fa-solid fa-check"></i> Confirm & Approve</button>
            </div>
        </div>
    </div>

    <!-- Media Choice Modal (Admin Blog) -->
    <div class="modal-overlay" id="mediaModal">
        <div class="modal-box">
            <h3><i class="fa-regular fa-images" style="margin-right: 0.5rem;"></i> Select Media</h3>
            <p>You can either upload an image directly, or paste an embed code (like a TikTok or YouTube iframe).</p>
            <input type="hidden" id="mediaTargetIndex">
            
            <div class="modal-actions" style="margin-top: 1rem; margin-bottom: 1.5rem;">
                <button class="modal-btn-confirm" onclick="triggerImageUploadFromModal()" style="background:var(--color-primary);"><i class="fa-solid fa-cloud-arrow-up"></i> Upload Image</button>
            </div>
            
            <div style="text-align:center; font-weight:bold; color:var(--color-text-muted); margin-bottom: 1rem;">OR</div>
            
            <div class="modal-form-group">
                <label>Paste Link or Embed Code (TikTok/YouTube)</label>
                <textarea id="embedMediaInput" rows="4" style="width: 100%; padding: 0.75rem 1rem; border: 1.5px solid var(--color-border); border-radius: 10px; font-family: monospace; font-size: 0.85rem; background: #F8FAFC;" placeholder='e.g. https://www.tiktok.com/@user/... or <iframe src="..." ></iframe>'></textarea>
            </div>
            
            <div class="modal-actions" style="margin-top: 1rem;">
                <button class="modal-btn-cancel" onclick="closeMediaModal()">Cancel</button>
                <button class="modal-btn-confirm" onclick="confirmEmbedMedia()"><i class="fa-solid fa-code"></i> Apply Embed</button>
            </div>
        </div>
    </div>

    <!-- File Upload Input -->
    <input type="file" id="hidden-file-input" accept="image/jpeg, image/png, image/webp, image/gif" style="display:none;">

    <!-- EmailJS SDK -->
    <script src="https://cdn.jsdelivr.net/npm/@emailjs/browser@4/dist/email.min.js"></script>

    <!-- Live Update Script -->
    <script>
        // ============================================================
        // EMAILJS CONFIGURATION
        // ============================================================
        const EMAILJS_PUBLIC_KEY = '6kbb6qQhV1ULh74SS';
        const EMAILJS_SERVICE_ID = 'service_5156493';
        const EMAILJS_TEMPLATE_ID = 'template_kyknzpn';
        // ============================================================

        // Initialize EmailJS
        emailjs.init(EMAILJS_PUBLIC_KEY);

        // Track which submissions are currently being actioned (prevent double-clicks)
        const actionInProgress = new Set();

        function escapeHtml(unsafe) {
            if (!unsafe) return '';
            return unsafe
                 .toString()
                 .replace(/&/g, "&amp;")
                 .replace(/</g, "&lt;")
                 .replace(/>/g, "&gt;")
                 .replace(/"/g, "&quot;")
                 .replace(/'/g, "&#039;");
        }
        // ---- Time Slot Formatter ----
        function formatTimeSlot(slot) {
            const timeMap = {
                'Morning': 'Morning (10:00 AM - 1:00 PM)',
                'Afternoon': 'Afternoon (1:00 PM - 4:00 PM)',
                'Evening': 'Late Afternoon (4:00 PM - 6:00 PM)'
            };
            return timeMap[slot] || slot;
        }

        // ---- Email Sending ----
        async function sendEmail(type, sub, revisedDate, revisedTime) {
            if (!sub.email) {
                console.warn('No email address for this submission, skipping email.');
                alert('This patient did not provide an email address. Status was updated but no email was sent.');
                return false;
            }

            const patientName = (sub.first_name || '') + ' ' + (sub.last_name || '');
            const service = sub.service || sub.type || 'your request';
            const originalDate = sub.date || 'N/A';
            const originalTime = formatTimeSlot(sub.time || 'N/A');

            let subject = '';
            let message = '';

            if (type === 'approved') {
                subject = 'Appointment Confirmed — Mijon Skin & Plastic Surgery Hospital';
                const dateTimeStr = `<b><u>${originalDate} at ${originalTime}</u></b>`;
                message = `Great news! Your appointment for <b>${service}</b> on ${dateTimeStr} has been approved.<br><br>` +
                          `Please arrive at the clinic 10 minutes before your scheduled time.<br><br>` + 
                          `If you have any questions, feel free to contact us.<br><br>` +
                          `Warm regards,<br><b>Mijon Skin & Plastic Surgery Hospital Pvt. Ltd.</b><br>Damak, Jhapa`;
            } else if (type === 'edited') {
                const newTime = formatTimeSlot(revisedTime);
                subject = 'Appointment Rescheduled — Mijon Skin & Plastic Surgery Hospital';
                const revisedInfo = `<b><u>${revisedDate} during ${newTime}</u></b>`;
                message = `Your appointment for <b>${service}</b> has been rescheduled.<br><br>` +
                          `<b>Original:</b> ${originalDate} at ${originalTime}<br>` +
                          `<b>Revised: ${revisedInfo}</b><br><br>` +
                          `Your revised appointment has been approved. Please arrive at the clinic during your revised time slot.<br><br>` +
                          `If you have any questions, feel free to contact us.<br><br>` + 
                          `Warm regards,<br><b>Mijon Skin & Plastic Surgery Hospital Pvt. Ltd.</b><br>Damak, Jhapa`;
            } else if (type === 'denied') {
                subject = 'Appointment Update — Mijon Skin & Plastic Surgery Hospital';
                const deniedStatus = `<b style="color: red;"><u>DENIED</u></b>`;
                message = `We regret to inform you that your appointment for <b>${service}</b> on ${originalDate} has been ${deniedStatus} and could not be accommodated at this time.<br><br>` +
                           `Please contact our clinic directly at <b>023-585411 / 9825951131 / 9842764665</b> for more information.<br><br>` +
                           `Warm regards,<br><b>Mijon Skin & Plastic Surgery Hospital Pvt. Ltd.</b><br>Damak, Jhapa`;
            }

            try {
                await emailjs.send(EMAILJS_SERVICE_ID, EMAILJS_TEMPLATE_ID, {
                    to_email: sub.email,
                    name: 'Mijon Skin Hospital',
                    patient_name: patientName,
                    title: subject,
                    message: message,
                    email: sub.email
                });
                return true;
            } catch (err) {
                console.error('EmailJS failed:', err);
                alert('Email failed to send. Check console for details. Status was still updated.');
                return false;
            }
        }

        // ---- Backend Status Update ----
        async function updateStatus(lineIndex, status, revisedDate, revisedTime) {
            const body = { lineIndex, status };
            if (revisedDate) body.revised_date = revisedDate;
            if (revisedTime) body.revised_time = revisedTime;

            const res = await fetch('api/update_submission.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(body)
            });
            return res.ok;
        }

        // ---- Action Handlers ----
        async function handleApprove(lineIndex, sub) {
            if (actionInProgress.has(lineIndex)) return;
            if (!confirm('Approve this submission? An email will be sent to the patient.')) return;
            actionInProgress.add(lineIndex);

            const ok = await updateStatus(lineIndex, 'approved');
            if (ok) {
                await sendEmail('approved', sub);
                fetchSubmissions();
            } else { alert('Failed to update status.'); }
            actionInProgress.delete(lineIndex);
        }

        async function handleDeny(lineIndex, sub) {
            if (actionInProgress.has(lineIndex)) return;
            if (!confirm('Deny this submission? An email will be sent to the patient.')) return;
            actionInProgress.add(lineIndex);

            const ok = await updateStatus(lineIndex, 'denied');
            if (ok) {
                await sendEmail('denied', sub);
                fetchSubmissions();
            } else { alert('Failed to update status.'); }
            actionInProgress.delete(lineIndex);
        }

        // ---- Edit Modal ----
        let editSubData = null;

        function openEditModal(lineIndex, sub) {
            editSubData = sub;
            document.getElementById('editLineIndex').value = lineIndex;
            document.getElementById('editDate').value = sub.date || '';
            document.getElementById('editPatientInfo').innerHTML = `<strong>${escapeHtml((sub.first_name||'') + ' ' + (sub.last_name||''))}</strong> — ${escapeHtml(sub.service || sub.type || 'N/A')}`;
            // Set time dropdown
            const timeSelect = document.getElementById('editTime');
            if (sub.time) {
                for (let i = 0; i < timeSelect.options.length; i++) {
                    if (timeSelect.options[i].value === sub.time) { timeSelect.selectedIndex = i; break; }
                }
            }
            document.getElementById('editModal').classList.add('active');
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.remove('active');
            editSubData = null;
        }

        async function confirmEdit() {
            const lineIndex = parseInt(document.getElementById('editLineIndex').value);
            const revisedDate = document.getElementById('editDate').value;
            const revisedTime = document.getElementById('editTime').value;

            if (!revisedDate) { alert('Please select a date.'); return; }
            if (actionInProgress.has(lineIndex)) return;
            actionInProgress.add(lineIndex);

            const ok = await updateStatus(lineIndex, 'edited', revisedDate, revisedTime);
            if (ok) {
                await sendEmail('edited', editSubData, revisedDate, revisedTime);
                closeEditModal();
                fetchSubmissions();
            } else { alert('Failed to update status.'); }
            actionInProgress.delete(lineIndex);
        }

        // ---- Render Helpers ----
        function formatDetails(data) {
            let html = '';
            
            if (data.service) {
                html += `<div class="meta-detail">
                            <span class="meta-label">Requested Service:</span> 
                            ${escapeHtml(data.service)}
                         </div>`;
            }
            
            if (data.date) {
                const d = new Date(data.date);
                const options = { month: 'short', day: 'numeric', year: 'numeric' };
                const prefDate = isNaN(d) ? escapeHtml(data.date) : d.toLocaleDateString('en-US', options);
                const prefTime = data.time ? ` (${escapeHtml(data.time)})` : '';
                
                const today = new Date(); today.setHours(0,0,0,0);
                const bookingDay = new Date(d); bookingDay.setHours(0,0,0,0);
                const diffDays = Math.round((bookingDay - today) / (1000 * 60 * 60 * 24));
                
                let dateColor = '#6B7280', dateBg = '#F3F4F6', dateLabel = '';
                
                if (diffDays < 0) { dateColor = '#9CA3AF'; dateBg = '#F9FAFB'; dateLabel = 'Past'; }
                else if (diffDays === 0) { dateColor = '#DC2626'; dateBg = '#FEF2F2'; dateLabel = 'Today'; }
                else if (diffDays === 1) { dateColor = '#EA580C'; dateBg = '#FFF7ED'; dateLabel = 'Tomorrow'; }
                else if (diffDays === 2) { dateColor = '#CA8A04'; dateBg = '#FEFCE8'; dateLabel = 'In 2 days'; }
                else if (diffDays <= 6) { dateColor = '#16A34A'; dateBg = '#F0FDF4'; dateLabel = 'In ' + diffDays + ' days'; }
                else { dateColor = '#2563EB'; dateBg = '#EFF6FF'; dateLabel = 'In ' + diffDays + ' days'; }
                
                html += `<div class="meta-detail" style="display: inline-flex; align-items: center; gap: 0.5rem; flex-wrap: wrap;">
                            <span class="meta-label">Preferred Timing:</span> 
                            <span style="background: ${dateBg}; color: ${dateColor}; padding: 0.2rem 0.6rem; border-radius: 6px; font-weight: 600; font-size: 0.85rem; border: 1px solid ${dateColor}20;">
                                ${prefDate}${prefTime}
                            </span>
                            ${dateLabel ? `<span style="background: ${dateColor}; color: white; padding: 0.15rem 0.5rem; border-radius: 9999px; font-size: 0.7rem; font-weight: 600;">${dateLabel}</span>` : ''}
                         </div>`;
            }

            // Show revised info if edited
            if (data.status === 'edited' && (data.revised_date || data.revised_time)) {
                const rd = data.revised_date ? new Date(data.revised_date).toLocaleDateString('en-US', {month:'short',day:'numeric',year:'numeric'}) : '';
                const rt = data.revised_time || '';
                const origDate = data.date ? new Date(data.date).toLocaleDateString('en-US', {month:'short',day:'numeric',year:'numeric'}) : 'N/A';
                const origTime = data.time || 'N/A';
                html += `<div class="revised-info">
                            <div><span class="old-time">${origDate} (${origTime})</span></div>
                            <div style="margin-top:0.25rem;"><i class="fa-solid fa-arrow-right" style="font-size:0.7rem; margin-right:0.3rem;"></i> <span class="new-time">${rd} (${rt})</span></div>
                         </div>`;
            }
            
            if (data.message) {
                const safeMessage = escapeHtml(data.message).replace(/\n/g, '<br>');
                html += `<div class="message-box">"${safeMessage}"</div>`;
            }
            
            return html;
        }

        function renderActionColumn(sub) {
            const idx = sub.lineIndex;

            // If already actioned, show status badge
            if (sub.status === 'approved') {
                return `<span class="status-badge status-approved"><i class="fa-solid fa-circle-check"></i> Approved</span>`;
            }
            if (sub.status === 'denied') {
                return `<span class="status-badge status-denied"><i class="fa-solid fa-circle-xmark"></i> Denied</span>`;
            }
            if (sub.status === 'edited') {
                return `<span class="status-badge status-edited"><i class="fa-solid fa-calendar-check"></i> Revised & Approved</span>`;
            }

            // Serialize submission data for inline onclick (minimal safe encoding)
            const subJson = btoa(unescape(encodeURIComponent(JSON.stringify(sub))));

            return `<div class="action-btns">
                <button class="action-btn btn-approve" onclick="handleApprove(${idx}, JSON.parse(decodeURIComponent(escape(atob('${subJson}')))))"><i class="fa-solid fa-check"></i> Approve</button>
                <button class="action-btn btn-edit-action" onclick="openEditModal(${idx}, JSON.parse(decodeURIComponent(escape(atob('${subJson}')))))"><i class="fa-solid fa-pen"></i> Edit</button>
                <button class="action-btn btn-deny" onclick="handleDeny(${idx}, JSON.parse(decodeURIComponent(escape(atob('${subJson}')))))"><i class="fa-solid fa-xmark"></i> Deny</button>
            </div>`;
        }

        // ---- Data Fetching ----
        async function fetchSubmissions() {
            try {
                const response = await fetch('api/get_submissions.php');
                if (!response.ok) {
                    if (response.status === 401) { window.location.reload(); }
                    return;
                }
                const data = await response.json();
                
                document.getElementById('total-bookings-count').innerText = data.totalBookings;
                document.getElementById('total-contacts-count').innerText = data.totalContacts;
                document.getElementById('total-chatleads-count').innerText = data.totalChatLeads || 0;
                
                const allSubs = data.submissions || [];
                const bookings = allSubs.filter(s => s.type && s.type.toLowerCase() === 'booking');
                const contacts = allSubs.filter(s => !s.type || s.type.toLowerCase() !== 'booking');

                // Render Bookings Table
                const bookingsTbody = document.getElementById('bookings-table-body');
                if (bookings.length === 0) {
                    bookingsTbody.innerHTML = `
                        <tr><td colspan="4">
                            <div class="empty-state">
                                <i class="fa-solid fa-calendar-xmark empty-icon"></i>
                                <h3>No bookings yet</h3>
                                <p>When patients book appointments online, they will appear here.</p>
                            </div>
                        </td></tr>`;
                } else {
                    let bookingHtml = '';
                    bookings.forEach(sub => {
                        let formattedDate = 'Unknown Date', formattedTime = '';
                        if (sub.timestamp) {
                            try {
                                const tDate = new Date(sub.timestamp);
                                if (!isNaN(tDate)) {
                                    formattedDate = tDate.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
                                    formattedTime = tDate.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit' });
                                }
                            } catch(e) {}
                        }
                        const name = escapeHtml((sub.first_name || '') + ' ' + (sub.last_name || ''));
                        const phone = escapeHtml(sub.phone || 'N/A');
                        const emailHtml = sub.email ? `<div class="patient-contact"><i class="fa-solid fa-envelope"></i> ${escapeHtml(sub.email)}</div>` : '';

                        bookingHtml += `
                            <tr class="row-booking">
                                <td>
                                    <div style="font-weight:500; color:var(--color-primary); margin-bottom:0.25rem;">${formattedDate}</div>
                                    <div style="color:var(--color-text-muted); font-size:0.85rem;">${formattedTime}</div>
                                </td>
                                <td>
                                    <span class="patient-name">${name}</span>
                                    <div class="patient-contact"><i class="fa-solid fa-phone"></i> ${phone}</div>
                                    ${emailHtml}
                                </td>
                                <td>${formatDetails(sub)}</td>
                                <td>${renderActionColumn(sub)}</td>
                            </tr>`;
                    });
                    bookingsTbody.innerHTML = bookingHtml;
                }

                // Render Contacts Table
                const contactsTbody = document.getElementById('contacts-table-body');
                if (contacts.length === 0) {
                    contactsTbody.innerHTML = `
                        <tr><td colspan="3">
                            <div class="empty-state">
                                <i class="fa-solid fa-envelope-circle-check empty-icon"></i>
                                <h3>No contact messages yet</h3>
                                <p>When visitors submit the contact form, their messages will appear here.</p>
                            </div>
                        </td></tr>`;
                } else {
                    let contactHtml = '';
                    contacts.forEach(sub => {
                        let formattedDate = 'Unknown Date', formattedTime = '';
                        if (sub.timestamp) {
                            try {
                                const tDate = new Date(sub.timestamp);
                                if (!isNaN(tDate)) {
                                    formattedDate = tDate.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
                                    formattedTime = tDate.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit' });
                                }
                            } catch(e) {}
                        }
                        const name = escapeHtml((sub.first_name || '') + ' ' + (sub.last_name || ''));
                        const phone = escapeHtml(sub.phone || 'N/A');
                        const emailHtml = sub.email ? `<div class="patient-contact"><i class="fa-solid fa-envelope"></i> ${escapeHtml(sub.email)}</div>` : '';
                        const message = sub.message ? `<div class="message-box">"${escapeHtml(sub.message).replace(/\n/g, '<br>')}"</div>` : '<span style="color:var(--color-text-muted); font-style: italic;">No message provided.</span>';

                        contactHtml += `
                            <tr class="row-contact">
                                <td>
                                    <div style="font-weight:500; color:var(--color-primary); margin-bottom:0.25rem;">${formattedDate}</div>
                                    <div style="color:var(--color-text-muted); font-size:0.85rem;">${formattedTime}</div>
                                </td>
                                <td>
                                    <span class="patient-name">${name}</span>
                                    <div class="patient-contact"><i class="fa-solid fa-phone"></i> ${phone}</div>
                                    ${emailHtml}
                                </td>
                                <td>${message}</td>
                            </tr>`;
                    });
                    contactsTbody.innerHTML = contactHtml;
                }

                // ---- Render Chat Leads Table ----
                const chatLeads = data.chatLeads || [];
                const chatTbody = document.getElementById('chatleads-table-body');
                if (chatLeads.length === 0) {
                    chatTbody.innerHTML = `
                        <tr><td colspan="3">
                            <div class="empty-state">
                                <i class="fa-solid fa-comments empty-icon"></i>
                                <h3>No chat leads yet</h3>
                                <p>When visitors use the chat widget, their inquiries will appear here.</p>
                            </div>
                        </td></tr>`;
                } else {
                    let chatHtml = '';
                    chatLeads.forEach(lead => {
                        let formattedDate = 'Unknown Date', formattedTime = '';
                        if (lead.timestamp) {
                            try {
                                const tDate = new Date(lead.timestamp);
                                if (!isNaN(tDate)) {
                                    formattedDate = tDate.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
                                    formattedTime = tDate.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit' });
                                }
                            } catch(e) {}
                        }
                        const name = escapeHtml(lead.name || 'N/A');
                        const phone = escapeHtml(lead.phone || 'N/A');
                        const category = escapeHtml(lead.category || '');
                        const subcategory = escapeHtml(lead.subcategory || '');
                        const firstVisit = lead.first_visit === 'Yes' ? '<span style="background:#D1FAE5;color:#065F46;padding:2px 8px;border-radius:9999px;font-size:0.75rem;font-weight:600;">First Visit</span>' : '<span style="background:#DBEAFE;color:#1E40AF;padding:2px 8px;border-radius:9999px;font-size:0.75rem;font-weight:600;">Returning</span>';
                        const details = escapeHtml(lead.details || '');

                        chatHtml += `
                            <tr style="border-left: 4px solid #D4AF37;">
                                <td>
                                    <div style="font-weight:500; color:var(--color-primary); margin-bottom:0.25rem;">${formattedDate}</div>
                                    <div style="color:var(--color-text-muted); font-size:0.85rem;">${formattedTime}</div>
                                </td>
                                <td>
                                    <span class="patient-name">${name}</span>
                                    <div class="patient-contact"><i class="fa-solid fa-phone"></i> ${phone}</div>
                                    <div style="margin-top:0.4rem;">${firstVisit}</div>
                                </td>
                                <td>
                                    <div class="meta-detail"><span class="meta-label">Interest:</span> ${category}</div>
                                    ${subcategory && subcategory !== 'General Inquiry' ? `<div class="meta-detail"><span class="meta-label">Specific:</span> ${subcategory}</div>` : ''}
                                    <div class="message-box" style="margin-top:0.5rem; border-left-color: #D4AF37;">${details}</div>
                                </td>
                            </tr>`;
                    });
                    chatTbody.innerHTML = chatHtml;
                }
                
            } catch (error) {
                console.error("Failed to fetch data:", error);
            }
        }

        // ---- Tab Switching ----
        const allNavItems = ['nav-bookings', 'nav-contacts', 'nav-chatleads', 'nav-blog'];
        const allViews = ['view-bookings', 'view-contacts', 'view-chatleads', 'view-blog'];

        function switchTab(activeNavId, activeViewId) {
            allNavItems.forEach(id => document.getElementById(id).classList.remove('active'));
            allViews.forEach(id => {
                const el = document.getElementById(id);
                if (el) el.classList.remove('active');
            });
            document.getElementById(activeNavId).classList.add('active');
            document.getElementById(activeViewId).classList.add('active');
        }

        document.getElementById('nav-bookings').addEventListener('click', (e) => {
            e.preventDefault();
            switchTab('nav-bookings', 'view-bookings');
            document.querySelector('.page-title').textContent = 'Booking Requests';
        });

        document.getElementById('nav-contacts').addEventListener('click', (e) => {
            e.preventDefault();
            switchTab('nav-contacts', 'view-contacts');
            document.querySelector('.page-title').textContent = 'Contact Messages';
        });

        document.getElementById('nav-chatleads').addEventListener('click', (e) => {
            e.preventDefault();
            switchTab('nav-chatleads', 'view-chatleads');
            document.querySelector('.page-title').textContent = 'Chat Leads';
        });

        document.getElementById('nav-blog').addEventListener('click', (e) => {
            e.preventDefault();
            switchTab('nav-blog', 'view-blog');
            document.querySelector('.page-title').textContent = 'Blog Editor';
            loadBlogsForEditor();
        });

        // ---- Mobile Nav Logic ----
        function toggleMobileNav() {
            const container = document.getElementById('mobile-nav-container');
            if(container) container.classList.toggle('open');
        }

        function handleMobileNav(e, navId, viewId, titleText, triggerHtml) {
            if(e) e.preventDefault();
            
            // Close menu
            const container = document.getElementById('mobile-nav-container');
            if(container) container.classList.remove('open');
            
            // Update trigger UI
            const triggerSpan = document.querySelector('.dropdown-trigger span');
            if(triggerSpan) triggerSpan.innerHTML = triggerHtml;
            
            // Sync active state in mobile menu
            document.querySelectorAll('.dropdown-item').forEach(el => el.classList.remove('active'));
            if(e && e.currentTarget) e.currentTarget.classList.add('active');

            // Trigger actual tab switch
            if (navId === 'nav-blog') {
                switchTab('nav-blog', 'view-blog');
                document.querySelector('.page-title').textContent = 'Blog Editor';
                loadBlogsForEditor();
            } else {
                switchTab(navId, viewId);
                let displayTitle = titleText;
                if (titleText === 'Contacts') displayTitle = 'Contact Messages';
                if (titleText === 'Bookings') displayTitle = 'Booking Requests';
                document.querySelector('.page-title').textContent = displayTitle;
            }
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            const container = document.getElementById('mobile-nav-container');
            if (container && !container.contains(e.target) && container.classList.contains('open')) {
                container.classList.remove('open');
            }
        });

        // ---- Blog Editor Logic ----
        let cachedBlogs = [];
        let isEditMode = false;

        function toggleEditMode() {
            isEditMode = !isEditMode;
            const container = document.getElementById('blog-editor-container');
            const btn = document.getElementById('btn-toggle-edit');
            if(isEditMode) {
                container.classList.add('editor-mode-active');
                btn.classList.add('active');
                btn.innerHTML = '<i class="fa-solid fa-xmark"></i> Disable Editing';
                document.querySelectorAll('.editable-field').forEach(el => el.setAttribute('contenteditable', 'true'));
            } else {
                container.classList.remove('editor-mode-active');
                btn.classList.remove('active');
                btn.innerHTML = '<i class="fa-solid fa-pen-to-square"></i> Enable Editing';
                document.querySelectorAll('.editable-field').forEach(el => el.setAttribute('contenteditable', 'false'));
            }
        }

        function openMediaModal(index) {
            if(!isEditMode) return;
            document.getElementById('mediaTargetIndex').value = index;
            document.getElementById('embedMediaInput').value = '';
            document.getElementById('mediaModal').classList.add('active');
        }

        function closeMediaModal() {
            document.getElementById('mediaModal').classList.remove('active');
        }

        function triggerImageUploadFromModal() {
            closeMediaModal();
            const index = document.getElementById('mediaTargetIndex').value;
            const input = document.getElementById('hidden-file-input');
            input.dataset.targetIndex = index;
            input.click();
        }

        function confirmEmbedMedia() {
            const index = document.getElementById('mediaTargetIndex').value;
            let embedCode = document.getElementById('embedMediaInput').value.trim();
            if(!embedCode) { showToast('Please paste a link or embed code first.', 'error'); return; }
            
            // Auto-convert standard URLs to iframes
            if (!embedCode.startsWith('<')) {
                let ytMatch = embedCode.match(/(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))([\w-]{11})/);
                let ttMatch = embedCode.match(/tiktok\.com\/@[\w.-]+\/video\/(\d+)/);
                
                if (ttMatch && ttMatch[1]) {
                    // MUST use /embed/v2/ format for TikTok to allow connections in iframes
                    embedCode = '<iframe src="https://www.tiktok.com/embed/v2/' + ttMatch[1] + '?loop=1&rel=0" width="100%" height="600" frameborder="0" scrolling="no" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
                } else if (ytMatch && ytMatch[1]) {
                    // rel=0 ensures it doesn't show videos from other channels at the end
                    embedCode = '<iframe width="100%" height="400" src="https://www.youtube.com/embed/' + ytMatch[1] + '?rel=0" frameborder="0" scrolling="no" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
                } else {
                    showToast('Could not auto-detect video player. Please paste the full HTML Embed Code instead.', 'error');
                    return;
                }
            }
            
            cachedBlogs[index].mediaType = 'embed';
            cachedBlogs[index].image = embedCode; // Store embed HTML in the image field
            
            closeMediaModal();
            renderBlogEditor();
        }

        function addNewBlog() {
            cachedBlogs.unshift({
                id: Date.now().toString(),
                title: "New Blog Post Title",
                date: new Date().toLocaleDateString('en-US', {month:'short',day:'numeric',year:'numeric'}),
                tag: "UPDATE",
                mediaType: "image",
                image: "images/favicon.png", 
                content: "<p>Write your amazing blog description here. You can use bold text, or lists.</p><ul><li>First point</li><li>Second point</li></ul>",
                reverse: false
            });
            renderBlogEditor();
            if(!isEditMode) toggleEditMode();
        }

        document.getElementById('hidden-file-input').addEventListener('change', async function() {
            const file = this.files[0];
            if(!file) return;
            const targetIndex = this.dataset.targetIndex;
            
            const overlay = document.querySelector(`#blog-post-${targetIndex} .img-upload-overlay`);
            const prevHtml = overlay.innerHTML;
            overlay.innerHTML = '<i class="fa-solid fa-spinner fa-spin fa-2x"></i><span>Uploading...</span>';
            
            const formData = new FormData();
            formData.append('image', file);

            try {
                const res = await fetch('api/upload_image.php', { method: 'POST', body: formData });
                const data = await res.json();
                if(data.success) {
                    cachedBlogs[targetIndex].mediaType = 'image';
                    cachedBlogs[targetIndex].image = data.path;
                    renderBlogEditor();
                } else {
                    showToast('Upload failed: ' + data.error, 'error');
                }
            } catch(e) {
                showToast('Upload failed due to network error.', 'error');
            } finally {
                overlay.innerHTML = prevHtml;
                this.value = ''; // Reset input
            }
        });

        function toggleReverseLayout(index, checkbox) {
            const postEl = document.getElementById(`blog-post-${index}`);
            if(checkbox.checked) {
                postEl.classList.add('reverse');
            } else {
                postEl.classList.remove('reverse');
            }
        }

        async function loadBlogsForEditor() {
            const container = document.getElementById('blog-editor-container');
            container.innerHTML = `<div style="text-align: center; padding: 3rem;"><i class="fa-solid fa-circle-notch fa-spin" style="font-size: 2rem; color: var(--color-border); margin-bottom: 1rem;"></i><p style="color: var(--color-text-muted);">Loading blog data...</p></div>`;
            
            try {
                const res = await fetch('api/get_blogs.php');
                if (!res.ok) throw new Error('Failed to fetch blogs');
                cachedBlogs = await res.json();
                renderBlogEditor();
            } catch (error) {
                container.innerHTML = `<div style="text-align: center; padding: 3rem; color: #DC2626;"><p>Error loading blogs. Please try again.</p></div>`;
            }
        }

        function renderBlogEditor() {
            const container = document.getElementById('blog-editor-container');
            container.innerHTML = '';
            
            cachedBlogs.forEach((blog, index) => {
                const wrapper = document.createElement('div');
                wrapper.style.marginBottom = '3rem';
                
                const layoutToggle = document.createElement('div');
                layoutToggle.style.display = 'flex';
                layoutToggle.style.justifyContent = 'space-between';
                layoutToggle.style.alignItems = 'center';
                layoutToggle.style.marginBottom = '1rem';
                layoutToggle.innerHTML = `
                    <label style="display:inline-flex; align-items:center; gap:0.5rem; cursor:pointer; font-weight:600; color:var(--color-primary); background:#F3F4F6; padding:0.5rem 1rem; border-radius:8px;">
                        <input type="checkbox" onchange="toggleReverseLayout(${index}, this)" id="blog-reverse-${index}" ${blog.reverse ? 'checked' : ''} style="width:auto;"> Display Image on Right Side
                    </label>
                    ${isEditMode ? '<button class="action-btn btn-deny" onclick="deleteBlog(' + index + ')" style="padding: 0.5rem 1rem;"><i class="fa-solid fa-trash"></i> Delete Post</button>' : ''}
                `;
                wrapper.appendChild(layoutToggle);

                const card = document.createElement('article');
                card.id = `blog-post-${index}`;
                card.className = 'blog-post ' + (blog.reverse ? 'reverse' : '');
                
                card.innerHTML = `
                    <div class="blog-img-wrapper">
                        ${blog.mediaType === 'embed' ? blog.image.replace('<iframe', '<iframe scrolling="no"') : `<img src="${escapeHtml(blog.image)}" class="blog-img" alt="${escapeHtml(blog.title)}">`}
                        <div class="img-upload-overlay" onclick="openMediaModal(${index})">
                            <i class="fa-solid fa-cloud-arrow-up fa-2x"></i>
                            <span>Change Media (Upload or Embed)</span>
                        </div>
                    </div>
                    <div class="blog-content">
                        <div class="blog-meta">
                            <div><i class="fa-regular fa-calendar" style="margin-right: 0.4rem;"></i><span class="editable-field" id="blog-date-${index}" ${isEditMode?'contenteditable="true"':'contenteditable="false"'}>${escapeHtml(blog.date)}</span></div>
                            <div><i class="fa-solid fa-tag" style="margin-right: 0.4rem;"></i><span class="editable-field" id="blog-tag-${index}" ${isEditMode?'contenteditable="true"':'contenteditable="false"'}>${escapeHtml(blog.tag)}</span></div>
                        </div>
                        <h2 class="blog-title editable-field" id="blog-title-${index}" ${isEditMode?'contenteditable="true"':'contenteditable="false"'}>${escapeHtml(blog.title)}</h2>
                        <div class="blog-text editable-field" id="blog-content-${index}" ${isEditMode?'contenteditable="true"':'contenteditable="false"'} style="min-height: 100px;">
                            ${blog.content}
                        </div>
                    </div>
                `;
                wrapper.appendChild(card);
                container.appendChild(wrapper);
            });

            if(isEditMode) {
                container.classList.add('editor-mode-active');
            }
        }

        function deleteBlog(index) {
            if(confirm('Are you sure you want to delete this blog post?')) {
                cachedBlogs.splice(index, 1);
                renderBlogEditor();
                showToast('Blog removed. Click "Save All Changes" to make it live.', 'success');
            }
        }

        function showToast(message, type = 'success') {
            const container = document.getElementById('toast-container') || (function() {
                const div = document.createElement('div');
                div.id = 'toast-container';
                div.style.cssText = 'position:fixed; top:20px; left:50%; transform:translateX(-50%); z-index:9999; display:flex; flex-direction:column; gap:10px; pointer-events:none;';
                document.body.appendChild(div);
                return div;
            })();
            const toast = document.createElement('div');
            const bgStr = type === 'success' ? '#10B981' : '#EF4444';
            toast.style.cssText = `background: ${bgStr}; color: white; padding: 12px 24px; border-radius: 8px; font-weight: 600; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); transform: translateY(-20px); opacity: 0; transition: all 0.3s ease; font-family: var(--font-display); font-size: 0.95rem; display: flex; align-items: center; gap: 0.5rem;`;
            toast.innerHTML = `<i class="fa-solid ${type === 'success' ? 'fa-check-circle' : 'fa-circle-exclamation'}"></i> ${message}`;
            container.appendChild(toast);
            void toast.offsetWidth; // trigger reflow
            toast.style.transform = 'translateY(0)';
            toast.style.opacity = '1';
            setTimeout(() => {
                toast.style.transform = 'translateY(-20px)';
                toast.style.opacity = '0';
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }

        async function saveAllBlogs() {
            const btn = document.getElementById('global-save-btn');
            const originalText = btn.innerHTML;
            btn.innerHTML = `<i class="fa-solid fa-spinner fa-spin"></i> Saving...`;
            btn.disabled = true;

            const newBlogs = [];
            for (let i = 0; i < cachedBlogs.length; i++) {
                newBlogs.push({
                    id: cachedBlogs[i].id,
                    title: document.getElementById(`blog-title-${i}`).innerText.trim(),
                    date: document.getElementById(`blog-date-${i}`).innerText.trim(),
                    tag: document.getElementById(`blog-tag-${i}`).innerText.trim(),
                    mediaType: cachedBlogs[i].mediaType || 'image',
                    image: cachedBlogs[i].image,
                    content: document.getElementById(`blog-content-${i}`).innerHTML.trim(),
                    reverse: document.getElementById(`blog-reverse-${i}`).checked
                });
            }

            try {
                const res = await fetch('api/save_blogs.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(newBlogs)
                });
                
                const result = await res.json();
                if (result.success) {
                    showToast('All blog posts saved successfully! The live site has been updated.', 'success');
                    cachedBlogs = newBlogs;
                    renderBlogEditor(); // re-render to update image previews and titles
                } else {
                    showToast('Failed to save blogs: ' + (result.error || 'Unknown error'), 'error');
                }
            } catch (error) {
                showToast('An error occurred while saving the blogs.', 'error');
            } finally {
                btn.innerHTML = originalText;
                btn.disabled = false;
            }
        }

        fetchSubmissions();
        setInterval(() => {
            // Only auto-refresh if bookings or contacts view is active
            if (document.getElementById('view-bookings').classList.contains('active') ||
                document.getElementById('view-contacts').classList.contains('active') ||
                document.getElementById('view-chatleads').classList.contains('active')) {
                fetchSubmissions();
            }
        }, 5000);
    </script>
<?php endif; ?>

</body>
</html>
