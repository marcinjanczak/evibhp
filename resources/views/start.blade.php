<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset ('css/style.css') }}">
    <title>Start</title>
</head>
<body>
   <body>
    <header>
        <div class="logo">System BHP PRO</div>
        <div class="user-info">
            <span>Jan Kowalski</span>
            <div class="user-avatar">JK</div>
        </div>
    </header>
    
    <nav>
        <ul>
            <li><a href="#">Dashboard</a></li>
            <li><a href="#">Ewidencja</a></li>
            <li><a href="#">Magazyn</a></li>
            <li><a href="#">Pracownicy</a></li>
            <li><a href="#">Raporty</a></li>
            <li><a href="#">Ustawienia</a></li>
        </ul>
    </nav>
    
    <div class="container">
        <h2>Podsumowanie</h2>
        
        <div class="dashboard">
            <div class="card">
                <h3>Ubrania do wydania</h3>
                <div class="stat">24</div>
                <p>Komplety wymagające wydania pracownikom</p>
                <a href="#" class="btn">Przejdź do listy</a>
            </div>
            
            <div class="card">
                <h3>W magazynie</h3>
                <div class="stat">156</div>
                <p>Dostępnych kompletów ubran BHP</p>
                <a href="#" class="btn">Sprawdź stan</a>
            </div>
            
            <div class="card">
                <h3>Do wymiany</h3>
                <div class="stat">42</div>
                <p>Komplety wymagające wymiany</p>
                <a href="#" class="btn">Zobacz szczegóły</a>
            </div>
            
            <div class="card">
                <h3>Ostatnie wydania</h3>
                <ul style="list-style: none;">
                    <li>Jan Nowak - 05.08.2023</li>
                    <li>Anna Kowalska - 04.08.2023</li>
                    <li>Piotr Wiśniewski - 03.08.2023</li>
                </ul>
                <a href="#" class="btn">Historia wydań</a>
            </div>
        </div>
        
        <div style="margin-top: 2rem;">
            <h2>Szybkie akcje</h2>
            <div style="display: flex; gap: 1rem; margin-top: 1rem;">
                <a href="#" class="btn">Dodaj nowe ubrania</a>
                <a href="#" class="btn">Zarejestruj wydanie</a>
                <a href="#" class="btn">Generuj raport</a>
            </div>
        </div>
    </div>
    
    <footer>
        &copy; 2023 System Ewidencji Ubrania BHP | Wersja 1.0
    </footer>
</body>

</body>
</html>