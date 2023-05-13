Zadanie Laravel:
Wersja PHP min. 8.0
Stworzyć prosty projekt – listę zadań dla użytkowników - w Laravelu umożliwiający:
Część 1:
1. Rejestrację użytkownika wraz z potwierdzeniem adresu email w linku
2. Logowanie zarejestrowanego użytkownika
3. Wyświetlanie listy zadań do wykonania (indywidualna per użytkownik) wraz z możliwością
wyszukiwania po tytule, szczegółach i dacie (deadline) (za pomocą ajax, przetwarzanie server side).
4. Rozwinięcie szczegółów zadania ( na tej samej podstronie, ajax)
5. Możliwość oznaczania zadania jako wykonane / niewykonane (na tej samej podstronie, ajax)
6. Możliwość filtrowania zadań (pokaż tylko wykonane / niewykonane / wszystkie), (na tej samej
podstronie, ajax, przetwarzanie server side)
7. Możliwość edycji/dodawania zadania na osobnej podstronie (wraz z walidacją – czy zadanie ma
tytuł / szczegóły / datę (deadline))
8. Wylogowywanie
Część 2:
1. Zainstalować paczkę Laravel Route Attributes (https://github.com/spatie/laravel-route-attributes)
i korzystając z niej przerobić routing tak, aby był za pomocą mechanizmu atrybutów.
2. Ustawienie zadania jako wykonane ma wysyłać maila do użytkonika z informacją, że zadanie
wykonane.
3. Wszystkie akcje usera (logowanie, rejestracja, wylogowanie, nieudane logowanie) mają być
logowane w bazie danych

Założenia:
1. Zadanie do napisania w Laravelu, wersja najnowsza dostępna
2. Front do napisania w dowolnej technologii (przeferowana Inertia+Vue lub Blade, niedozwolone
Livewire, jeśli chodzi o wygląd (style etc.) można skorzystać z gotowej biblioteki np. Bootstrap lub
Tailwind CSS)
3. Dla ajaxa w JavaScripcie używać fetch lub axios
4. W przypadku nieudanej walidacji backend ma zwracać 422 i komunikaty walidacyjne, które mają
się pojawić pod polami np. „To pole nie może być puste”
5. Projekt ma być zabezpieczony przed działaniem na danych innych userów za pomocą Policies
lub Gates
6. Projekt ma mieć walidację za pomocą FormRequests
7. Logika działania na danych powinna być w Modelach lub Serwisach, Kontrollery powinny być
jak najmniejsze
8. Maile wysyłany za pomocą Mailable, wysyłany na wykonanie odpowiednich eventów
(Rejestracja, Oznaczenie zadania jako wykonane) za pomocą Event Listenera lub Event Subscribera
9. Logowanie akcji usera z punktu 3 części 2 też ma być wykonane na zasanie emitowania
odpowiednich eventów i łapania ich za pomocą Event Listenera lub Event Subscribera
10. Projekt ma być przygotowany na wielojęzyczność (np. brak zhardkodowanych komunikatów i
tekstów, mają być brane z plików translacyjnych)
11. Napisane pliki php mają używać strict_types, a w metodach zawsze ma być typ argumentu i typ
zwracany (w wersji 8.0 zawsze da się określić typ)
