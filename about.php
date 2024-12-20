<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Despre Proiect - Cinematograf</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>

<header>
    <h1>Aplicația Cinematograf</h1>
</header>

<nav>
    <ul>
        <li><a href="index.php">Acasă</a></li>
        <li><a href="about.php">Despre</a></li>
    </ul>
</nav>

<div class="container">
    <section>
        <h2>Prezentare generală</h2>
        <p>Aplicația Cinematograf este un sistem de gestionare a filmelor, programărilor și rezervărilor de bilete pentru un cinema. Aceasta permite utilizatorilor să vizualizeze filmele disponibile, să rezerve bilete și să verifice programările.</p>
    </section>

    <section>
        <h2>Arhitectura aplicației</h2>
        <p>Arhitectura include două roluri principale:</p>
        <ul>
            <li><strong>Utilizator:</strong> poate vizualiza filmele și programările și poate rezerva bilete.</li>
            <li><strong>Administrator:</strong> are acces la funcții suplimentare, precum adăugarea și modificarea informațiilor despre filme și programări.</li>
        </ul>
        <p>Aplicația are o structură modulară, organizată în mai multe fișiere PHP pentru gestionarea diferitelor componente și funcții.</p>
    </section>

    <section>
        <h2>Descrierea bazei de date</h2>
        <p>Baza de date a aplicației este construită folosind MySQL și conține următoarele tabele principale:</p>
        <ul>
            <li><strong>utilizatori</strong>: stochează informații despre utilizatori, incluzând rolul fiecăruia (user sau admin).</li>
            <li><strong>filme</strong>: conține detalii despre fiecare film, cum ar fi titlul, genul, durata și descrierea.</li>
            <li><strong>sali</strong>: conține informații despre sălile de cinema.</li>
            <li><strong>programari</strong>: stochează programările filmelor, incluzând ora și sala de proiecție.</li>
            <li><strong>rezervari</strong>: stochează informațiile despre rezervările de bilete pentru utilizatori.</li>
        </ul>
    </section>

    <section>
        <h2>Soluția de implementare propusă</h2>
        <p>Aplicația este implementată în PHP și utilizează XAMPP pentru serverul Apache și MySQL pentru gestionarea bazei de date. Datele sunt stocate și accesate printr-o serie de interogări MySQL, iar accesul este controlat prin funcționalitatea de autentificare și roluri specifice pentru utilizatori și administratori.</p>
        <p>Designul aplicației este responsiv, fiind construit folosind HTML și CSS, pentru a oferi o experiență plăcută atât pe desktop, cât și pe dispozitive mobile.</p>
    </section>
</div>

<footer>
    <p>&copy; 2024 Cinematograf. Toate drepturile rezervate.</p>
</footer>

<script src="js/scripts.js"></script>
</body>
</html>
