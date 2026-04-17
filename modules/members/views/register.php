<section class="hero animate-fade-in">
    <div class="container">
        <div class="card" style="max-width: 450px; margin: 0 auto; text-align: left;">
            <h2 class="text-center">Registrierung</h2>
            <p class="text-center">Erstelle dein Konto und starte die Optimierung deines BKWs.</p>

            <?php
            echo validation_errors();
            echo flashdata();
            ?>
            
            <form action="<?= BASE_URL ?>members/submit_registration" method="POST" style="margin-top: 2rem;">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Benutzername</label>
                    <input type="text" name="username" placeholder="Sonnenschein88" style="width: 100%; padding: 12px; border-radius: 8px; border: 1px solid var(--border-color); background: var(--bg-color); color: var(--text-primary);">
                </div>

                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">E-Mail Adresse</label>
                    <input type="email" name="email" placeholder="deine@email.de" style="width: 100%; padding: 12px; border-radius: 8px; border: 1px solid var(--border-color); background: var(--bg-color); color: var(--text-primary);">
                </div>
                
                <div style="margin-bottom: 2rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Passwort</label>
                    <input type="password" name="password" placeholder="••••••••" style="width: 100%; padding: 12px; border-radius: 8px; border: 1px solid var(--border-color); background: var(--bg-color); color: var(--text-primary);">
                </div>
                
                <button type="submit" name="submit" value="Registrieren" class="btn btn-primary" style="width: 100%;">Registrieren</button>
            </form>
            
            <div class="text-center" style="margin-top: 1.5rem;">
                <p style="font-size: 0.9rem;">Bereits ein Konto? <a href="login" style="color: hsl(var(--solar-yellow)); text-decoration: none; font-weight: 600;">Hier anmelden</a></p>
            </div>
        </div>
    </div>
</section>
