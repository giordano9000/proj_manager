## PROJ_MANAGER

Serie di API per la gestione di progetti e task.
<hr>
Tutte le rotte accettano solamente richieste con header <code>Content-type: application/json</code>.
<br>
Per fare questo è stato aggiunto il middleware globale <i>CheckJsonMiddleware</i>

<hr>

Di seguito alcune lib usate:
<ul>
    <li>L'autenticazione è gestita tramite <code>php-open-source-saver/jwt-auth</code></li>
    <li>La gestione degli Uuid è effettuata con <code>ramsey/uuid-doctrine</code></li>
    <li>Gli Enum sono gestiti con <code>bensampo/laravel-enum</code></li>
</ul>

<hr>

I feature test si trovano nelle dir:
<ul>
    <li>tests/auth</li>
    <li>tests/projects</li>
    <li>tests/tasks</li>
</ul>

Il file <code>postman_collection.json</code> contiene la lista delle chiamate a disposizione.
