# bysykkel
Liste over bysykkel-stasjoner, og hvor mange sykler/plasser som er tilgjengelige

Krever standard-oppsett for å kjøre PHP. Anbefaler XAMPP for å kjøre programmet på lokal-maskin, eventuelt finne en host-server.
For å laste ned XAMPP, bruk: https://www.apachefriends.org/index.html

Programmet sender to CURL-requests til Bysykkels API for å få data om stasjoner og antall plasser eller sykler som er ledige på hver av
disse stasjonene, og lister ut denne informasjonen i en enkel HTML-tabell.
