@extends('layouts.master')

@section('content')
    <h2>{{ Auth::user()->organization->name }}</h2>

    @if(!$valid)
        <div class="alert alert-danger" role="alert">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                 class="bi bi-exclamation-triangle-fill flex-shrink-0 me-2" viewBox="0 0 16 16" role="img"
                 aria-label="Warning:">
                <path
                    d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
            </svg>
            Es erfolgt derzeit keine Synchronisierung zum aPager und keine Alarmierung!
        </div>
        <b>Bitte überprüfe deine Zugehörgkeit zur Gruppe "Handyalarmierung" und dein Geburtsdatum.</b> <br />
        Änderungen kannst du über das 4JUH Formular <a href="https://www.4juh.de/workspaces/sanitaetsbereitschaft-wuerzburg/apps/form/zugaenge-edv-programme" target="_blank">Zugänge IT/Programme</a> beantragen.<br /><br />
    @else


    <h4 class="mt-3">Ich sehe hier keine synchronisierten Daten</h4>
    <div class="accordion" id="faqAccordion1">
        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faqAccordion1Collapse1" aria-expanded="true">
                    Ich bekomme eine rote Fehlermeldung "Es erfolgt derzeit keine Synchronisierung zum aPager und keine Alarmierung!" angezeigt
                </button>
            </h2>
            <div id="faqAccordion1Collapse1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion1">
                <div class="accordion-body">
                    <p>Das bedeutet, dass deine Daten vom HiOrg Server nicht korrekt und / oder unvollständig sind. Wichtig sind insbesondere dein Geburtsdatum und deine Zugehörgkeit zur HiOrg-Gruppe "Handyalarmierung". Mehr Informationen darüber, welche Daten aus HiOrg notwendig sind, findest du <a href="https://www.4juh.de/workspaces/sanitaetsbereitschaft-wuerzburg/apps/wiki/wissensdatenbank/list/view/9ec25c48-9ff6-4a09-b360-71ece63b29f2?currentLanguage=NONE" target="_blank">in der Wissensdatenbank in der 4juh Community</a>.
                        Änderungen deiner Daten in HiOrg kannst du über das 4JUH Formular <a href="https://www.4juh.de/workspaces/sanitaetsbereitschaft-wuerzburg/apps/form/zugaenge-edv-programme" target="_blank">Zugänge IT/Programme</a> beantragen.</p>
                </div>
            </div>
        </div>
        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqAccordion1Collapse2" aria-expanded="false">
                    Alle Daten sind in HiOrg vorhanden, es funktioniert aber trotzdem nicht
                </button>
            </h2>
            <div id="faqAccordion1Collapse2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion1">
                <div class="accordion-body">
                    Das ist ungewöhnlich, aber kann durchaus vorkommen. Bitte wende dich in diesem Fall ausschließlich per E-Mail an <a href="mailto:elw.wuerzburg@johanniter.de">elw.wuerzburg@johanniter.de</a> und schildere dein Problem. Wir werden uns dann umgehend um dein Anliegen kümmern.
                </div>
            </div>
        </div>
    </div>

    <h4 class="mt-3">Wie bekomme ich jetzt die Alarmierung auf mein Handy?</h4>
    <div class="accordion" id="faqAccordion2">
        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faqAccordion2Collapse1" aria-expanded="true">
                    Welche App benötige ich?
                </button>
            </h2>
            <div id="faqAccordion2Collapse1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion2">
                <div class="accordion-body">
                    <p>Wir nutzen die App "aPager PRO" von Alamos um euch auf eurem Handy Alarmierungen auszuspielen. Die App kann kostenlos aus dem <a href="https://play.google.com/store/apps/details?id=org.xcrypt.apager.android2&hl=de&gl=US" target="_blank">Google Play Store</a>, oder dem <a href="https://apps.apple.com/de/app/apager-pro/id958761234" target="_blank">iOS App Store</a> heruntergeladen werden.</p>
                </div>
            </div>
        </div>
        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqAccordion2Collapse2" aria-expanded="false" aria-controls="collapseTwo">
                    Was muss ich nach der Installation der App tun?
                </button>
            </h2>
            <div id="faqAccordion2Collapse2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion2">
                <div class="accordion-body">
                    <p>Die aPager PRO App muss nach der Installation noch konfiguriert werden. Dazu benötigst du eine Aktivierungsmail, die du dir <a href="{{ route('me.prov') }}">hier anfordern kannst</a>. Die E-Mail wird an die in HiOrg hinterlegte E-Mail Adresse gesendet. Bitte beachte, dass die Mail nur 24 Stunden gültig ist. Sollte die Mail nicht ankommen, prüfe bitte auch deinen Spam-Ordner.</p>
                </div>
            </div>
        </div>
    </div>
@endsection
