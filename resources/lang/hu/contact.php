<?php

declare(strict_types=1);

return [
    'thank_you' => 'Köszönjük!',
    'submission_received' => 'Üzenetét megkaptuk, hamarosan felvesszük Önnel a kapcsolatot.',
    'submit' => 'Üzenet küldése',
    'submitting' => 'Küldés...',

    'resource' => [
        'form_singular' => 'Kapcsolati űrlap',
        'form_plural' => 'Kapcsolati űrlapok',
        'submission_singular' => 'Megkeresés',
        'submission_plural' => 'Megkeresések',
    ],

    'sections' => [
        'form_details' => 'Űrlap adatai',
        'fields' => 'Mezők',
        'submission_settings' => 'Beküldés után',
        'crm' => 'CRM és értesítések',
        'design' => 'Megjelenés',
        'custom_css' => 'Egyedi CSS',
        'contact' => 'Kapcsolattartó',
        'submission_details' => 'Beküldés részletei',
        'embed_direct' => 'Közvetlen link',
        'embed_widget' => 'JS widget',
        'embed_iframe' => 'Iframe',
    ],

    'descriptions' => [
        'fields' => 'A név, az e-mail és az üzenet mindig megjelenik és kötelező.',
        'custom_css' => 'Az űrlap konténerére korlátozva. Az `@import`, `url(javascript:...)` és hasonló szerkezeteket eltávolítjuk.',
    ],

    'fields' => [
        'name' => 'Belső név',
        'slug' => 'URL azonosító',
        'title' => 'Címsor',
        'description' => 'Bevezető szöveg',
        'submit_label' => 'Gomb felirata',
        'active' => 'Aktív',
        'contact_name' => 'Név',
        'email' => 'E-mail',
        'phone' => 'Telefonszám',
        'company' => 'Cégnév',
        'subject' => 'Tárgy',
        'message' => 'Üzenet',
        'consent' => 'Hozzájárulás',
        'consent_label' => 'Hozzájárulás jelölőnégyzet szövege',
        'consented_at' => 'Hozzájárulás ideje',
        'thank_you_message' => 'Köszönő üzenet',
        'redirect_url' => 'Átirányítási URL',
        'create_lead' => 'CRM lead létrehozása',
        'notify_emails' => 'Értesítési e-mail címek',
        'add_email' => 'E-mail cím hozzáadása',
        'custom_css' => 'CSS',
        'submissions_count' => 'Beküldések',
        'created_at' => 'Létrehozva',
        'form' => 'Űrlap',
        'lead' => 'Lead',
        'submitted_at' => 'Beküldve',
        'page_url' => 'Forrásoldal',
        'ip_address' => 'IP-cím',
        'user_agent' => 'Böngésző',
    ],

    'helpers' => [
        'name' => 'Csak az adminban látszik.',
        'title' => 'Az űrlap fölött jelenik meg. Alapértelmezés: a belső név.',
        'consent_label' => 'Ha ki van töltve, a látogatónak be kell jelölnie (pl. adatkezelési tájékoztató) a küldés előtt.',
        'redirect_url' => 'Opcionális. Sikeres beküldés után ide irányítjuk a látogatót.',
        'create_lead' => 'Minden megkeresésből ügyfél + lead lesz a CRM-ben.',
        'notify_emails' => 'Ezek a címek minden megkeresésről e-mailt kapnak.',
    ],

    'visibility' => [
        'hidden' => 'Rejtett',
        'optional' => 'Opcionális',
        'required' => 'Kötelező',
    ],

    'design' => [
        'primary_color' => 'Fő szín',
        'radius_label' => 'Lekerekítés',
        'card_label' => 'Kártya stílusa',
        'input_bg_label' => 'Mezők háttere',
        'submit_label' => 'Gomb igazítása',
        'width_label' => 'Maximális szélesség',
        'radius' => ['none' => 'Nincs', 'sm' => 'Kicsi', 'md' => 'Közepes', 'lg' => 'Nagy'],
        'card' => ['flat' => 'Lapos', 'bordered' => 'Keretes', 'shadow' => 'Árnyékos'],
        'input_bg' => ['gray' => 'Szürke', 'white' => 'Fehér'],
        'submit' => ['left' => 'Balra', 'center' => 'Középre', 'full' => 'Teljes szélesség'],
        'width' => ['narrow' => 'Keskeny', 'default' => 'Alapértelmezett', 'wide' => 'Széles'],
    ],

    'actions' => [
        'embed_code' => 'Beágyazó kód',
        'close' => 'Bezárás',
        'preview' => 'Előnézet',
        'open_lead' => 'Lead megnyitása',
    ],

    'embed' => [
        'direct_desc' => 'Oszd meg ezt a linket, vagy használd gomb céljaként.',
        'widget_desc' => 'Ajánlott. Illeszd be oda, ahol az űrlapnak meg kell jelennie; a magassága automatikusan igazodik.',
        'iframe_desc' => 'Tartalék megoldás olyan oldalakhoz, ahol nem futhat script.',
    ],

    'errors' => [
        'not_found' => 'A kapcsolati űrlap nem található.',
        'rate_limit' => 'Túl sok próbálkozás. Kérjük, próbálja újra :seconds másodperc múlva.',
    ],

    'mail' => [
        'subject' => 'Új megkeresés: :form',
        'intro' => 'Új megkeresés érkezett a(z) „:form” kapcsolati űrlapon.',
    ],
];
