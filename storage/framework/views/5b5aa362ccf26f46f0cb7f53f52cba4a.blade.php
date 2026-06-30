
                <style>
                    /* ───────────── Background ───────────── */
                    html.fi { background: #0b1120 !important; }

                    .fi-simple-layout {
                        background:
                            radial-gradient(ellipse 80% 60% at 50% -20%, rgba(245, 158, 11, 0.08), transparent),
                            radial-gradient(ellipse 60% 50% at 80% 100%, rgba(245, 158, 11, 0.05), transparent),
                            linear-gradient(165deg, #0b1120 0%, #162033 40%, #0f172a 100%) !important;
                        position: relative !important;
                        overflow: hidden !important;
                    }

                    .fi-simple-layout::before {
                        content: "" !important;
                        position: fixed !important;
                        inset: 0 !important;
                        background-image:
                            linear-gradient(rgba(255, 255, 255, 0.025) 1px, transparent 1px),
                            linear-gradient(90deg, rgba(255, 255, 255, 0.025) 1px, transparent 1px) !important;
                        background-size: 64px 64px !important;
                        pointer-events: none !important;
                    }

                    .fi-simple-layout::after {
                        content: "" !important;
                        position: fixed !important;
                        top: -40% !important;
                        right: -15% !important;
                        width: 700px !important;
                        height: 700px !important;
                        background: radial-gradient(circle, rgba(245, 158, 11, 0.06), transparent 70%) !important;
                        pointer-events: none !important;
                    }

                    /* ───────────── Card ───────────── */
                    .fi-simple-main {
                        background: #ffffff !important;
                        border-radius: 20px !important;
                        box-shadow: 0 25px 60px -12px rgba(0, 0, 0, 0.4), 0 1px 3px rgba(0, 0, 0, 0.1) !important;
                        padding: 2.25rem 2rem 2rem !important;
                        position: relative !important;
                        overflow: hidden !important;
                    }

                    .fi-simple-main::before {
                        content: "" !important;
                        position: absolute !important;
                        top: 0 !important;
                        left: 0 !important;
                        right: 0 !important;
                        height: 4px !important;
                        background: linear-gradient(90deg, #f59e0b, #d97706, #b45309) !important;
                        pointer-events: none !important;
                    }

                    .fi-simple-page-content {
                        gap: 0 !important;
                    }

                    /* ───────────── Brand Header ───────────── */
                    .fi-simple-header {
                        padding: 0 !important;
                        margin-bottom: 1.75rem !important;
                        gap: 0 !important;
                    }

                    .fi-logo {
                        display: none !important;
                    }

                    .fi-simple-header-heading {
                        font-size: 1.375rem !important;
                        font-weight: 700 !important;
                        color: #0f172a !important;
                        letter-spacing: -0.025em !important;
                        margin: 0 !important;
                    }

                    /* ───────────── Form Fields ───────────── */
                    .fi-sc-form {
                        gap: 1.25rem !important;
                    }

                    .fi-fo-field-label-col {
                        gap: 0 !important;
                    }

                    .fi-fo-field-label-content {
                        font-size: 0.8125rem !important;
                        font-weight: 600 !important;
                        color: #334155 !important;
                    }

                    .fi-fo-field-label-required-mark {
                        color: #f59e0b !important;
                        font-weight: 700 !important;
                    }

                    .fi-input-wrp {
                        background: #f8fafc !important;
                        border: 1.5px solid #e2e8f0 !important;
                        border-radius: 10px !important;
                        box-shadow: none !important;
                        transition: all 0.2s ease !important;
                    }

                    .fi-input-wrp:focus-within {
                        border-color: #f59e0b !important;
                        background: #ffffff !important;
                        box-shadow: 0 0 0 4px rgba(245, 158, 11, 0.1) !important;
                    }

                    .fi-input-wrp.fi-invalid {
                        border-color: #ef4444 !important;
                        box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.08) !important;
                    }

                    input.fi-input {
                        font-size: 0.875rem !important;
                        color: #0f172a !important;
                        padding-top: 0.625rem !important;
                        padding-bottom: 0.625rem !important;
                    }

                    input.fi-input::placeholder {
                        color: #94a3b8 !important;
                        font-weight: 400 !important;
                    }

                    /* ───────────── Checkbox ───────────── */
                    .fi-fo-field-has-inline-label {
                        gap: 0.5rem !important;
                    }

                    .fi-fo-field-has-inline-label .fi-fo-field-label-content {
                        font-size: 0.8125rem !important;
                        font-weight: 500 !important;
                        color: #475569 !important;
                    }

                    .fi-checkbox-input {
                        border-radius: 6px !important;
                        border-color: #cbd5e1 !important;
                        transition: all 0.15s ease !important;
                    }

                    .fi-checkbox-input:checked {
                        background-color: #f59e0b !important;
                        border-color: #f59e0b !important;
                    }

                    /* ───────────── Submit Button ───────────── */
                    .fi-btn.fi-color-primary {
                        background: linear-gradient(135deg, #f59e0b, #d97706) !important;
                        border-radius: 10px !important;
                        padding: 0.75rem 1.5rem !important;
                        font-weight: 600 !important;
                        font-size: 0.875rem !important;
                        color: #fff !important;
                        border: none !important;
                        box-shadow: 0 4px 14px rgba(245, 158, 11, 0.3) !important;
                        transition: all 0.2s ease !important;
                        letter-spacing: 0.01em !important;
                    }

                    .fi-btn.fi-color-primary:hover {
                        transform: translateY(-2px) !important;
                        box-shadow: 0 8px 25px rgba(245, 158, 11, 0.4) !important;
                        background: linear-gradient(135deg, #fbbf24, #d97706) !important;
                    }

                    .fi-btn.fi-color-primary:active {
                        transform: translateY(0) !important;
                        box-shadow: 0 2px 8px rgba(245, 158, 11, 0.3) !important;
                    }

                    /* ───────────── Password Reset Link ───────────── */
                    .fi-fo-field-label .fi-link {
                        font-size: 0.8125rem !important;
                        color: #f59e0b !important;
                        font-weight: 500 !important;
                    }

                    .fi-fo-field-label .fi-link:hover {
                        color: #d97706 !important;
                        text-decoration: underline !important;
                    }

                    /* ───────────── Error Messages ───────────── */
                    .fi-fo-field-wrp-error-message {
                        font-size: 0.75rem !important;
                        font-weight: 500 !important;
                        color: #ef4444 !important;
                    }

                    /* ───────────── Custom Brand Header ───────────── */
                    .procell-login-header {
                        text-align: center;
                        margin-bottom: 1.75rem;
                    }

                    .procell-login-header .logo-wrap {
                        display: inline-flex;
                        align-items: center;
                        justify-content: center;
                        width: 52px;
                        height: 52px;
                        background: linear-gradient(135deg, #f59e0b, #d97706);
                        border-radius: 14px;
                        margin-bottom: 1.25rem;
                        box-shadow: 0 8px 24px rgba(245, 158, 11, 0.25);
                    }

                    .procell-login-header .logo-wrap svg {
                        display: block;
                    }

                    .procell-login-header h1 {
                        font-size: 1.375rem;
                        font-weight: 700;
                        color: #0f172a;
                        letter-spacing: -0.025em;
                        margin: 0 0 0.375rem 0;
                    }

                    .procell-login-header p {
                        font-size: 0.875rem;
                        color: #64748b;
                        margin: 0;
                        line-height: 1.5;
                    }

                    /* ───────────── Footer ───────────── */
                    .procell-login-footer {
                        text-align: center;
                        margin-top: 1.5rem;
                        padding-top: 1.25rem;
                        border-top: 1px solid #f1f5f9;
                    }

                    .procell-login-footer p {
                        font-size: 0.75rem;
                        color: #94a3b8;
                        margin: 0;
                    }

                    .procell-login-footer a {
                        color: #f59e0b;
                        font-weight: 500;
                        text-decoration: none;
                        transition: color 0.15s ease;
                    }

                    .procell-login-footer a:hover {
                        color: #d97706;
                        text-decoration: underline;
                    }
                </style>
            