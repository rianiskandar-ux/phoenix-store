<?php
/**
 * 
 * Array used by the shortcode [wbs_composite channels="<key>" label="true" desc=""true"]
 * 
 * @see includes/shortcodes.php
 * 
 * @since 2.0.0
 * 
 * Key <key> used for channels:
 *     - head[0-9]
 *     - core
 *     - theme_library
 *     - theme_whitelabel
 *     - language
 *     - mobile_app
 *     - email
 *     - phone
 *     - im
 *     - postmail
 *     - chat
 *     - manager
 *     - operator
 *     - agent
 * 
 * Key for current locale language:
 *     - English          : 'en_US'
 *     - Euskara          : 'eu_ES'
 *     - Bulgarian        : 'bg_BG'
 *     - Croatian         : 'hr'
 *     - Czech            : 'cs_CZ'
 *     - Danish           : 'da_DK'
 *     - Dutch            : 'nl_NL'
 *     - Estonian         : 'et'
 *     - Finnish          : 'fi'
 *     - French           : 'fr_FR'
 *     - German           : 'de_DE'
 *     - Greek            : 'el'
 *     - Hungarian        : 'hu_HU'
 *     - Irish            : 'ga_IE'
 *     - Italian          : 'it_IT'
 *     - Latvian          : 'lv_LV'
 *     - Lithuanian       : 'lt_LT'
 *     - Maltese          : 'mt_MT'
 *     - Norwegian Nynorsk: 'nb_NO'
 *     - Polish           : 'pl_PL'
 *     - Portuguese       : 'pt_PT'
 *     - Romanian         : 'ro_RO'
 *     - Slovak           : 'sk_SK'
 *     - Slovenian        : 'sl_SI'
 *     - Spanish          : 'es_ES'
 *     - Swedish          : 'sv_SE'
 * 
 * @link https://developer.wordpress.org/reference/functions/get_locale/
 */

/*core*/
return array(
    /**
     * Headings
     */
    'head1' => array(
        // English
        'en_US' => array( 
            'label' => null,
            'desc'  => "<strong>Please select the items you wish to purchase ✓ and then adjust the quantity for each selected item.</strong><br>Ensure that the checkboxes next to the items you intend to purchase are checked, and input the desired quantity. If the checkbox remains unchecked, it indicates that you have not selected the product item.",
        ),
        // Euskara
        'eu_ES' => array( 
            'label' => null,
            'desc'  => "<strong>Hautatu erosi nahi dituzun elementuak ✓ eta, ondoren, egokitu hautatutako elementu bakoitzaren kantitatea.</strong><br>Ziurtatu erosi nahi dituzun elementuen ondoan dauden kontrol-laukiak markatuta daudela eta idatzi nahi duzun kantitatea. Kontrol-laukia markatu gabe geratzen bada, produktuaren elementua ez duzula hautatu adierazten du.",
        ),
        // Bulgarian
        'bg_BG' => array(
            'label' => null,
            'desc'  => "<strong>Моля, изберете артикулите, които искате да закупите ✓ и след това коригирайте количеството за всеки избран артикул.</strong><br>Уверете се, че квадратчетата за отметка до артикулите, които възнамерявате да закупите, са отметнати и въведете желаното количество. Ако квадратчето за отметка остане без отметка, това означава, че не сте избрали продуктовия артикул.",
        ),
        // Croatian
        'hr' => array(
            'label' => null,
            'desc'  => "<strong>Odaberite stavke koje želite kupiti ✓, a zatim prilagodite količinu za svaku odabranu stavku.</strong><br>Provjerite jesu li označeni potvrdni okviri pored stavki koje namjeravate kupiti i unesite željenu količinu. Ako potvrdni okvir ostane neoznačen, to znači da niste odabrali stavku proizvoda.",
        ),
        // Czech
        'cs_CZ' => array(
            'label' => null,
            'desc'  => "<strong>Vyberte položky, které chcete zakoupit ✓, a poté upravte množství pro každou vybranou položku.</strong><br>Ujistěte se, že jsou zaškrtnuta políčka vedle položek, které chcete zakoupit, a zadejte požadované množství. Pokud zaškrtávací políčko zůstane nezaškrtnuté, znamená to, že jste nevybrali položku produktu.",
        ),
        // Danish
        'da_DK' => array(
            'label' => null,
            'desc'  => "<strong>Vælg venligst de varer, du ønsker at købe ✓ og juster derefter mængden for hver valgt vare.</strong><br>Sørg for, at afkrydsningsfelterne ud for de varer, du har til hensigt at købe, er markeret, og indtast det ønskede antal. Hvis afkrydsningsfeltet forbliver umarkeret, betyder det, at du ikke har valgt produktvaren.",
        ),
        // Dutch
        'nl_NL' => array(
            'label' => null,
            'desc'  => "<strong>Selecteer de artikelen die u wilt kopen ✓ en pas vervolgens het aantal voor elk geselecteerd artikel aan.</strong><br>Zorg ervoor dat de selectievakjes naast de artikelen die u wilt kopen zijn aangevinkt en voer het gewenste aantal in. Als het selectievakje uitgeschakeld blijft, betekent dit dat u het productitem niet hebt geselecteerd.",
        ),
        // Estonian
        'et' => array(
            'label' => null,
            'desc'  => "<strong>Valige kaubad, mida soovite osta ✓ ja seejärel kohandage iga valitud kauba kogust.</strong><br>Veenduge, et ostetavate kaupade kõrval olevad märkeruudud on märgitud, ja sisestage soovitud kogus. Kui märkeruut jääb märkimata, näitab see, et te pole tooteartiklit valinud.",
        ),
        // Finnish
        'fi' => array(
            'label' => null,
            'desc'  => "<strong>Valitse tuotteet, jotka haluat ostaa ✓ ja säädä sitten kunkin valitun tuotteen määrä.</strong><br>Varmista, että ostettavien tuotteiden vieressä olevat valintaruudut on valittu, ja syötä haluamasi määrä. Jos valintaruutua ei ole valittu, se tarkoittaa, että et ole valinnut tuotetta.",
        ),
        // French
        'fr_FR' => array(
            'label' => null,
            'desc'  => "<strong>Veuillez sélectionner les articles que vous souhaitez acheter ✓, puis ajustez la quantité pour chaque article sélectionné.</strong><br>Assurez-vous que les cases à côté des articles que vous avez l'intention d'acheter sont cochées et saisissez la quantité souhaitée. Si la case reste décochée, cela indique que vous n'avez pas sélectionné l'article du produit.",
        ),
        // German
        'de_DE' => array(
            'label' => null,
            'desc'  => "<strong>Bitte wählen Sie die Artikel aus, die Sie kaufen möchten ✓ und passen Sie dann die Menge für jeden ausgewählten Artikel an.</strong><br>Stellen Sie sicher, dass die Kontrollkästchen neben den Artikeln, die Sie kaufen möchten, aktiviert sind, und geben Sie die gewünschte Menge ein. Wenn das Kontrollkästchen deaktiviert bleibt, bedeutet dies, dass Sie den Produktartikel nicht ausgewählt haben.",
        ),
        // Greek
        'el' => array(
            'label' => null,
            'desc'  => "<strong>Επιλέξτε τα είδη που θέλετε να αγοράσετε ✓ και, στη συνέχεια, προσαρμόστε την ποσότητα για κάθε επιλεγμένο είδος.</strong><br>Βεβαιωθείτε ότι είναι επιλεγμένα τα πλαίσια ελέγχου δίπλα στα είδη που σκοπεύετε να αγοράσετε και εισαγάγετε την επιθυμητή ποσότητα. Εάν το πλαίσιο ελέγχου παραμένει μη επιλεγμένο, σημαίνει ότι δεν έχετε επιλέξει το στοιχείο προϊόντος.",
        ),
        // Hungarian
        'hu_HU' => array(
            'label' => null,
            'desc'  => "<strong>Kérjük, válassza ki a megvásárolni kívánt termékeket ✓, majd állítsa be a mennyiséget minden egyes kiválasztott cikkhez.</strong><br>Győződjön meg arról, hogy a megvásárolni kívánt cikkek melletti jelölőnégyzetek be vannak jelölve, és adja meg a kívánt mennyiséget. Ha a jelölőnégyzet nincs bejelölve, az azt jelzi, hogy nem választotta ki a terméket.",
        ),
        // Irish
        'ga_IE' => array(
            'label' => null,
            'desc'  => "<strong>Roghnaigh le do thoil na hearraí is mian leat a cheannach ✓ agus ansin coigeartaigh an chainníocht do gach earra roghnaithe.</strong><br>Cinntigh go bhfuil na ticbhoscaí in aice leis na hearraí atá beartaithe agat a cheannach, agus cuir isteach an chainníocht atá uait. Mura bhfuil an ticbhosca fós gan tic, léiríonn sé nach bhfuil an táirge roghnaithe agat.",
        ),
        // Italian
        'it_IT' => array(
            'label' => null,
            'desc'  => "<strong>Seleziona gli articoli che desideri acquistare ✓ quindi modifica la quantità per ciascun articolo selezionato.</strong><br>Assicurati che le caselle di controllo accanto agli articoli che intendi acquistare siano selezionate e inserisci la quantità desiderata. Se la casella di controllo rimane deselezionata, indica che non hai selezionato l'articolo del prodotto.",
        ),
        // Latvian
        'lv_LV' => array(
            'label' => null,
            'desc'  => "<strong>Lūdzu, atlasiet preces, kuras vēlaties iegādāties ✓ un pēc tam pielāgojiet katras atlasītās preces daudzumu.</strong><br>Pārliecinieties, ka ir atzīmētas izvēles rūtiņas blakus precēm, kuras plānojat iegādāties, un ievadiet vajadzīgo daudzumu. Ja izvēles rūtiņa paliek neatzīmēta, tas norāda, ka neesat atlasījis produkta vienību.",
        ),
        // Lithuanian
        'lt_LT' => array(
            'label' => null,
            'desc'  => "<strong>Pasirinkite norimas įsigyti prekes ✓ ir pakoreguokite kiekvienos pasirinktos prekės kiekį.</strong><br>Įsitikinkite, kad yra pažymėti žymimieji laukeliai šalia prekių, kurias ketinate įsigyti, ir įveskite norimą kiekį. Jei žymės langelis lieka nepažymėtas, tai reiškia, kad nepasirinkote prekės prekės.",
        ),
        // Maltese
        'mt_MT' => array(
            'label' => null,
            'desc'  => "<strong>Jekk jogħġbok agħżel l-oġġetti li tixtieq tixtri ✓ u mbagħad aġġusta l-kwantità għal kull oġġett magħżul.</strong><br>Aċċerta li l-kaxxi ta’ kontroll ħdejn l-oġġetti li biħsiebek tixtri huma ċċekkjati, u daħħal il-kwantità mixtieqa. Jekk il-kaxxa ta' kontroll tibqa' mhux ikkontrollata, tindika li ma għażiltx l-oġġett tal-prodott.",
        ),
        // Norwegian Nynorsk
        'nb_NO' => array(
            'label' => null,
            'desc'  => "<strong>Vennligst velg varene du ønsker å kjøpe ✓ og juster deretter antallet for hver valgt vare.</strong><br>Sørg for at avmerkingsboksene ved siden av varene du har tenkt å kjøpe er merket av, og skriv inn ønsket antall. Hvis avmerkingsboksen forblir umerket, indikerer det at du ikke har valgt produktelementet.",
        ),
        // Polish
        'pl_PL' => array(
            'label' => null,
            'desc'  => "<strong>Wybierz produkty, które chcesz kupić ✓, a następnie dostosuj ilość każdego wybranego przedmiotu.</strong><br>Upewnij się, że pola wyboru obok przedmiotów, które zamierzasz kupić, są zaznaczone i wprowadź żądaną ilość. Jeśli checkbox pozostanie niezaznaczony, oznacza to, że nie wybrałeś pozycji produktu.",
        ),
        // Portuguese
        'pt_PT' => array(
            'label' => null,
            'desc'  => "<strong>Selecione os itens que deseja comprar ✓ e ajuste a quantidade de cada item selecionado.</strong><br>Certifique-se de que as caixas de seleção ao lado dos itens que você pretende comprar estejam marcadas e insira a quantidade desejada. Se a caixa de seleção permanecer desmarcada, indica que você não selecionou o item do produto.",
        ),
        // Romanian
        'ro_RO' => array(
            'label' => null,
            'desc'  => "<strong>Selectați articolele pe care doriți să le achiziționați ✓ și apoi ajustați cantitatea pentru fiecare articol selectat.</strong><br>Asigurați-vă că casetele de selectare de lângă articolele pe care intenționați să le achiziționați sunt bifate și introduceți cantitatea dorită. Dacă caseta de selectare rămâne nebifată, înseamnă că nu ați selectat articolul de produs.",
        ),
        // Slovak
        'sk_SK' => array(
            'label' => null,
            'desc'  => "<strong>Vyberte položky, ktoré chcete kúpiť ✓ a potom upravte množstvo pre každú vybratú položku.</strong><br>Uistite sa, že sú začiarknuté políčka vedľa položiek, ktoré chcete kúpiť, a zadajte požadované množstvo. Ak začiarkavacie políčko zostane nezačiarknuté, znamená to, že ste nevybrali položku produktu.",
        ),
        // Slovenian
        'sl_SI' => array(
            'label' => null,
            'desc'  => "<strong>Prosimo, izberite artikle, ki jih želite kupiti ✓ in nato prilagodite količino za vsak izbran artikel.</strong><br>Prepričajte se, da so potrditvena polja poleg elementov, ki jih nameravate kupiti, potrjena, in vnesite želeno količino. Če potrditveno polje ostane nepotrjeno, to pomeni, da artikla izdelka niste izbrali.",
        ),
        // Spanish
        'es_ES' => array(
            'label' => null,
            'desc'  => "<strong>Seleccione los artículos que desea comprar ✓ y luego ajuste la cantidad de cada artículo seleccionado.</strong><br>Asegúrese de que las casillas de verificación junto a los artículos que desea comprar estén marcadas e ingrese la cantidad deseada. Si la casilla de verificación permanece sin marcar, indica que no ha seleccionado el artículo del producto.",
        ),
        // Swedish
        'sv_SE' => array(
            'label' => null,
            'desc'  => "<strong>Välj de varor du vill köpa ✓ och justera sedan kvantiteten för varje vald vara.</strong><br>Se till att kryssrutorna bredvid de varor du tänker köpa är markerade och ange önskad kvantitet. Om kryssrutan förblir omarkerad betyder det att du inte har valt produktobjektet.",
        ),
    ),
    'head2' => array(
        // English
        'en_US' => array( 
            'label' => null,
            'desc'  => "Please select a subscription period: <strong>monthly</strong> or <strong>yearly</strong>",
        ),
        // Euskara
        'eu_ES' => array( 
            'label' => null,
            'desc'  => "Hautatu harpidetza-epe bat: <strong>hilero</strong> edo <strong>urtero</strong>",
        ),
        // Bulgarian
        'bg_BG' => array(
            'label' => null,
            'desc'  => "Моля, изберете период на абонамент: <strong>месечен</strong> или <strong>годишен</strong>",
        ),
        // Croatian
        'hr' => array(
            'label' => null,
            'desc'  => "Odaberite razdoblje pretplate: <strong>mjesečno</strong> ili <strong>godišnje</strong>",
        ),
        // Czech
        'cs_CZ' => array(
            'label' => null,
            'desc'  => "Vyberte prosím období předplatného: <strong>měsíční</strong> nebo <strong>roční</strong>",
        ),
        // Danish
        'da_DK' => array(
            'label' => null,
            'desc'  => "Vælg venligst en abonnementsperiode: <strong>månedlig</strong> eller <strong>årlig</strong>",
        ),
        // Dutch
        'nl_NL' => array(
            'label' => null,
            'desc'  => "Selecteer een abonnementsperiode: <strong>maandelijks</strong> of <strong>jaarlijks</strong>",
        ),
        // Estonian
        'et' => array(
            'label' => null,
            'desc'  => "Valige tellimisperiood: <strong>kuu</strong> või <strong>aastane</strong>",
        ),
        // Finnish
        'fi' => array(
            'label' => null,
            'desc'  => "Valitse tilausjakso: <strong>kuukausi</strong> tai <strong>vuosittain</strong>",
        ),
        // French
        'fr_FR' => array(
            'label' => null,
            'desc'  => "Veuillez sélectionner une période d'abonnement : <strong>mensuel</strong> ou <strong>annuel</strong>",
        ),
        // German
        'de_DE' => array(
            'label' => null,
            'desc'  => "Bitte wählen Sie einen Abonnementzeitraum: <strong>monatlich</strong> oder <strong>jährlich</strong>",
        ),
        // Greek
        'el' => array(
            'label' => null,
            'desc'  => "Επιλέξτε μια περίοδο συνδρομής: <strong>μηνιαία</strong> ή <strong>ετήσια</strong>",
        ),
        // Hungarian
        'hu_HU' => array(
            'label' => null,
            'desc'  => "Kérjük, válasszon előfizetési időszakot: <strong>havi</strong> vagy <strong>évente</strong>",
        ),
        // Irish
        'ga_IE' => array(
            'label' => null,
            'desc'  => "Roghnaigh tréimhse síntiúis: <strong>go míosúil</strong> nó <strong>go bliantúil</strong>",
        ),
        // Italian
        'it_IT' => array(
            'label' => null,
            'desc'  => "Seleziona un periodo di abbonamento: <strong>mensile</strong> o <strong>annuale</strong>",
        ),
        // Latvian
        'lv_LV' => array(
            'label' => null,
            'desc'  => "Lūdzu, atlasiet abonēšanas periodu: <strong>mēnesi</strong> vai <strong>ik gadu</strong>",
        ),
        // Lithuanian
        'lt_LT' => array(
            'label' => null,
            'desc'  => "Pasirinkite prenumeratos laikotarpį: <strong>mėnesį</strong> arba <strong>metus</strong>",
        ),
        // Maltese
        'mt_MT' => array(
            'label' => null,
            'desc'  => "Jekk jogħġbok agħżel perjodu ta' abbonament: <strong>kull xahar</strong> jew <strong>kull sena</strong>",
        ),
        // Norwegian Nynorsk
        'nb_NO' => array(
            'label' => null,
            'desc'  => "Velg en abonnementsperiode: <strong>månedlig</strong> eller <strong>årlig</strong>",
        ),
        // Polish
        'pl_PL' => array(
            'label' => null,
            'desc'  => "Wybierz okres subskrypcji: <strong>miesięczny</strong> lub <strong>roczny</strong>",
        ),
        // Portuguese
        'pt_PT' => array(
            'label' => null,
            'desc'  => "Selecione um período de assinatura: <strong>mensal</strong> ou <strong>anual</strong>",
        ),
        // Romanian
        'ro_RO' => array(
            'label' => null,
            'desc'  => "Vă rugăm să selectați o perioadă de abonament: <strong>lunar</strong> sau <strong>anual</strong>",
        ),
        // Slovak
        'sk_SK' => array(
            'label' => null,
            'desc'  => "Vyberte obdobie odberu: <strong>mesačné</strong> alebo <strong>ročné</strong>",
        ),
        // Slovenian
        'sl_SI' => array(
            'label' => null,
            'desc'  => "Izberite obdobje naročnine: <strong>mesečno</strong> ali <strong>letno</strong>",
        ),
        // Spanish
        'es_ES' => array(
            'label' => null,
            'desc'  => "Seleccione un período de suscripción: <strong>mensual</strong> o <strong>anual</strong>",
        ),
        // Swedish
        'sv_SE' => array(
            'label' => null,
            'desc'  => "Välj en prenumerationsperiod: <strong>månatlig</strong> eller <strong>årlig</strong>",
        ),
    ),
    'head3' => array(
        // English
        'en_US' => array( 
            'label' => null,
            'desc'  => "Features",
        ),
        // Euskara
        'eu_ES' => array( 
            'label' => null,
            'desc'  => "Ezaugarriak",
        ),
        // Bulgarian
        'bg_BG' => array(
            'label' => null,
            'desc'  => "Характеристика",
        ),
        // Croatian
        'hr' => array(
            'label' => null,
            'desc'  => "Značajke",
        ),
        // Czech
        'cs_CZ' => array(
            'label' => null,
            'desc'  => "Funkce",
        ),
        // Danish
        'da_DK' => array(
            'label' => null,
            'desc'  => "Funktioner",
        ),
        // Dutch
        'nl_NL' => array(
            'label' => null,
            'desc'  => "Functies",
        ),
        // Estonian
        'et' => array(
            'label' => null,
            'desc'  => "Funktsioonid",
        ),
        // Finnish
        'fi' => array(
            'label' => null,
            'desc'  => "ominaisuudet",
        ),
        // French
        'fr_FR' => array(
            'label' => null,
            'desc'  => "Fonctionnalités",
        ),
        // German
        'de_DE' => array(
            'label' => null,
            'desc'  => "Merkmale",
        ),
        // Greek
        'el' => array(
            'label' => null,
            'desc'  => "Χαρακτηριστικά",
        ),
        // Hungarian
        'hu_HU' => array(
            'label' => null,
            'desc'  => "Jellemzők",
        ),
        // Irish
        'ga_IE' => array(
            'label' => null,
            'desc'  => "Gnéithe",
        ),
        // Italian
        'it_IT' => array(
            'label' => null,
            'desc'  => "Caratteristiche",
        ),
        // Latvian
        'lv_LV' => array(
            'label' => null,
            'desc'  => "Iespējas",
        ),
        // Lithuanian
        'lt_LT' => array(
            'label' => null,
            'desc'  => "funkcijos",
        ),
        // Maltese
        'mt_MT' => array(
            'label' => null,
            'desc'  => "Karatteristiċi",
        ),
        // Norwegian Nynorsk
        'nb_NO' => array(
            'label' => null,
            'desc'  => "Egenskaper",
        ),
        // Polish
        'pl_PL' => array(
            'label' => null,
            'desc'  => "Cechy",
        ),
        // Portuguese
        'pt_PT' => array(
            'label' => null,
            'desc'  => "Características",
        ),
        // Romanian
        'ro_RO' => array(
            'label' => null,
            'desc'  => "Caracteristici",
        ),
        // Slovak
        'sk_SK' => array(
            'label' => null,
            'desc'  => "Vlastnosti",
        ),
        // Slovenian
        'sl_SI' => array(
            'label' => null,
            'desc'  => "Lastnosti",
        ),
        // Spanish
        'es_ES' => array(
            'label' => null,
            'desc'  => "Características",
        ),
        // Swedish
        'sv_SE' => array(
            'label' => null,
            'desc'  => "Funktioner",
        ),
    ),
    'head4' => array(
        // English
        'en_US' => array( 
            'label' => null,
            'desc'  => "Channels",
        ),
        // Euskara
        'eu_ES' => array( 
            'label' => null,
            'desc'  => "Kanalak",
        ),
        // Bulgarian
        'bg_BG' => array(
            'label' => null,
            'desc'  => "Канали",
        ),
        // Croatian
        'hr' => array(
            'label' => null,
            'desc'  => "Kanali",
        ),
        // Czech
        'cs_CZ' => array(
            'label' => null,
            'desc'  => "Kanály",
        ),
        // Danish
        'da_DK' => array(
            'label' => null,
            'desc'  => "Kanaler",
        ),
        // Dutch
        'nl_NL' => array(
            'label' => null,
            'desc'  => "Kanalen",
        ),
        // Estonian
        'et' => array(
            'label' => null,
            'desc'  => "Kanalid",
        ),
        // Finnish
        'fi' => array(
            'label' => null,
            'desc'  => "Kanavat",
        ),
        // French
        'fr_FR' => array(
            'label' => null,
            'desc'  => "Canaux",
        ),
        // German
        'de_DE' => array(
            'label' => null,
            'desc'  => "Kanäle",
        ),
        // Greek
        'el' => array(
            'label' => null,
            'desc'  => "Κανάλια",
        ),
        // Hungarian
        'hu_HU' => array(
            'label' => null,
            'desc'  => "Csatornák",
        ),
        // Irish
        'ga_IE' => array(
            'label' => null,
            'desc'  => "Cainéil",
        ),
        // Italian
        'it_IT' => array(
            'label' => null,
            'desc'  => "Canali",
        ),
        // Latvian
        'lv_LV' => array(
            'label' => null,
            'desc'  => "Kanāli",
        ),
        // Lithuanian
        'lt_LT' => array(
            'label' => null,
            'desc'  => "Kanalai",
        ),
        // Maltese
        'mt_MT' => array(
            'label' => null,
            'desc'  => "Kanali",
        ),
        // Norwegian Nynorsk
        'nb_NO' => array(
            'label' => null,
            'desc'  => "Kanaler",
        ),
        // Polish
        'pl_PL' => array(
            'label' => null,
            'desc'  => "Kanały",
        ),
        // Portuguese
        'pt_PT' => array(
            'label' => null,
            'desc'  => "Canais",
        ),
        // Romanian
        'ro_RO' => array(
            'label' => null,
            'desc'  => "Canale",
        ),
        // Slovak
        'sk_SK' => array(
            'label' => null,
            'desc'  => "Kanály",
        ),
        // Slovenian
        'sl_SI' => array(
            'label' => null,
            'desc'  => "Kanali",
        ),
        // Spanish
        'es_ES' => array(
            'label' => null,
            'desc'  => "Canales",
        ),
        // Swedish
        'sv_SE' => array(
            'label' => null,
            'desc'  => "Kanaler",
        ),
    ),
    'head5' => array(
        // English
        'en_US' => array( 
            'label' => null,
            'desc'  => "User Accounts",
        ),
        // Euskara
        'eu_ES' => array( 
            'label' => null,
            'desc'  => "Erabiltzaile-kontuak",
        ),
        // Bulgarian
        'bg_BG' => array(
            'label' => null,
            'desc'  => "Потребителски акаунти",
        ),
        // Croatian
        'hr' => array(
            'label' => null,
            'desc'  => "Korisnički računi",
        ),
        // Czech
        'cs_CZ' => array(
            'label' => null,
            'desc'  => "Uživatelské účty",
        ),
        // Danish
        'da_DK' => array(
            'label' => null,
            'desc'  => "Brugerkonti",
        ),
        // Dutch
        'nl_NL' => array(
            'label' => null,
            'desc'  => "Gebruikersaccounts",
        ),
        // Estonian
        'et' => array(
            'label' => null,
            'desc'  => "Kasutajakontod",
        ),
        // Finnish
        'fi' => array(
            'label' => null,
            'desc'  => "Käyttäjätilit",
        ),
        // French
        'fr_FR' => array(
            'label' => null,
            'desc'  => "Comptes utilisateur",
        ),
        // German
        'de_DE' => array(
            'label' => null,
            'desc'  => "Benutzerkonten",
        ),
        // Greek
        'el' => array(
            'label' => null,
            'desc'  => "Λογαριασμοί χρηστών",
        ),
        // Hungarian
        'hu_HU' => array(
            'label' => null,
            'desc'  => "Felhasználói fiókok",
        ),
        // Irish
        'ga_IE' => array(
            'label' => null,
            'desc'  => "Cuntais Úsáideora",
        ),
        // Italian
        'it_IT' => array(
            'label' => null,
            'desc'  => "Profili utente",
        ),
        // Latvian
        'lv_LV' => array(
            'label' => null,
            'desc'  => "Lietotāju konti",
        ),
        // Lithuanian
        'lt_LT' => array(
            'label' => null,
            'desc'  => "Vartotojų paskyros",
        ),
        // Maltese
        'mt_MT' => array(
            'label' => null,
            'desc'  => "Kontijiet tal-Utenti",
        ),
        // Norwegian Nynorsk
        'nb_NO' => array(
            'label' => null,
            'desc'  => "Brukerkontoer",
        ),
        // Polish
        'pl_PL' => array(
            'label' => null,
            'desc'  => "Konta użytkowników",
        ),
        // Portuguese
        'pt_PT' => array(
            'label' => null,
            'desc'  => "Contas de usuário",
        ),
        // Romanian
        'ro_RO' => array(
            'label' => null,
            'desc'  => "Conturi de utilizator",
        ),
        // Slovak
        'sk_SK' => array(
            'label' => null,
            'desc'  => "Používateľské účty",
        ),
        // Slovenian
        'sl_SI' => array(
            'label' => null,
            'desc'  => "Uporabniški računi",
        ),
        // Spanish
        'es_ES' => array(
            'label' => null,
            'desc'  => "Cuentas de usuario",
        ),
        // Swedish
        'sv_SE' => array(
            'label' => null,
            'desc'  => "Cuentas de usuario",
        ),
    ),
    /**
     * Core
     */
    'core' => array(
        // English
        'en_US' => array( 
            'label' => "Dedicated website + Questionnaire",
            'desc'  => "A dedicated platform with its unique address forms the cornerstone of the Phoenix Whistleblowing application. You can select from three types of questionnaires. With these components in place, your whistleblowing site is fully operational and live.",
        ),
        // Euskara
        'eu_ES' => array( 
            'label' => "Web gune berezia + Galdera-ordenantza",
            'desc'  => "Phoenix Whistleblowing aplikazioaren oinarri nagusia bere helbide berezia duen plataforma zehatz batek osatzen du. Hiru motatako galderetako bat hautatu dezakezu. Elementu hauekin eginda, zure zirriborroaren gunea erabat operatiboa eta bizi da.",
        ),
        // Bulgarian
        'bg_BG' => array(
            'label' => "Посветен уебсайт + Анкета",
            'desc'  => "Посветена платформа с уникален адрес е основата на приложението за сигнализиране на нарушения в „Финикс“. Можете да избирате от три вида анкети. С тези компоненти на място, вашият сайт за сигнализиране на нарушения е напълно оперативен и жив.",
        ),
        // Croatian
        'hr' => array(
            'label' => "Posvećena web stranica + Upitnik",
            'desc'  => "Posvećena platforma s njezinom jedinstvenom adresom tvori temelj aplikacije Phoenix Whistleblowing. Možete odabrati između tri vrste upitnika. S ovim komponentama na mjestu, vaša web stranica za prijavu nepravilnosti potpuno je operativna i aktivna.",
        ),
        // Czech
        'cs_CZ' => array(
            'label' => "Dedicovaná webová stránka + Dotazník",
            'desc'  => "Dedikovaná platforma s jejím jedinečným adresou tvoří základní kámen aplikace Phoenix Whistleblowing. Můžete vybírat ze tří typů dotazníků. S těmito komponenty na místě je vaše webstránka pro oznamování nepravostí plně provozuschopná a živá.",
        ),
        // Danish
        'da_DK' => array(
            'label' => "Dedikeret hjemmeside + Spørgeskema",
            'desc'  => "En dedikeret platform med sin unikke adresse udgør hjørnestenen i Phoenix Whistleblowing-applikationen. Du kan vælge mellem tre typer spørgeskemaer. Med disse komponenter på plads er dit whistleblowing-sted fuldt operationelt og live.",
        ),
        // Dutch
        'nl_NL' => array(
            'label' => "Toegewijde website + Vragenlijst",
            'desc'  => "Een toegewijd platform met zijn unieke adres vormt de hoeksteen van de Phoenix Klokkenluidersapplicatie. U kunt kiezen uit drie soorten vragenlijsten. Met deze componenten op hun plaats is uw klokkenluiderssite volledig operationeel en actief.",
        ),
        // Estonian
        'et' => array(
            'label' => "Pühendatud veebisait + küsimustik",
            'desc'  => "Pühendatud platvorm koos selle ainulaadse aadressiga moodustab Phoenixi Whistleblowing rakenduse alustala. Saate valida kolme tüüpi küsimustikust. Nende komponentide olemasolul on teie teabeleht täielikult töökorras ja veebis.",
        ),
        // Finnish
        'fi' => array(
            'label' => "Omistettu verkkosivusto + Kyselylomake",
            'desc'  => "Omistettu alusta ainutlaatuisella osoitteella muodostaa Phoenixin ilmiantosovelluksen kulmakiven. Voit valita kolmesta eri kyselylomakkeesta. Kun nämä osat ovat paikallaan, ilmiantosivustosi on täysin toiminnassa ja käytössä.",
        ),
        // French
        'fr_FR' => array(
            'label' => "Site web dédié + Questionnaire",
            'desc'  => "Une plateforme dédiée avec son adresse unique constitue la pierre angulaire de l'application de dénonciation de Phoenix. Vous pouvez choisir parmi trois types de questionnaires. Avec ces composants en place, votre site de dénonciation est entièrement opérationnel et en direct.",
        ),
        // German
        'de_DE' => array(
            'label' => "Dedizierte Website + Fragebogen",
            'desc'  => "Eine dedizierte Plattform mit ihrer einzigartigen Adresse bildet das Fundament der Phoenix Whistleblowing-Anwendung. Sie können aus drei Arten von Fragebögen wählen. Mit diesen Komponenten an Ort und Stelle ist Ihre Hinweisgeber-Website vollständig funktionsfähig und live.",
        ),
        // Greek
        'el' => array(
            'label' => "Αφιερωμένη ιστοσελίδα + Ερωτηματολόγιο",
            'desc'  => "Μια αφιερωμένη πλατφόρμα με τη μοναδική της διεύθυνση αποτελεί την κεντρική πέτρα της εφαρμογής Phoenix Whistleblowing. Μπορείτε να επιλέξετε από τρία είδη ερωτηματολογίων. Με αυτά τα στοιχεία στη θέση τους, η ιστοσελίδα σας για αναφορά παραβιάσεων λειτουργεί πλήρως και είναι ζωντανή.",
        ),
        // Hungarian
        'hu_HU' => array(
            'label' => "Különálló weboldal + Kérdőív",
            'desc'  => "Egy különálló platform, saját egyedi címmel alkotja a Phoenix Whistleblowing alkalmazás alapját. Háromféle kérdőípből választhat. Ezekkel az összetevőkkel a bejelentési oldal teljesen működőképes és élő.",
        ),
        // Irish
        'ga_IE' => array(
            'label' => "Suíomh Gréasáin Tiomnaithe + Ceistneoir",
            'desc'  => "Is é an plátaform tiomnaithe leis an seoladh uathúil a fhoirmíonn cnámh cloch an iarratais Phoenix Whistleblowing. Is féidir leat roghnú idir trí chineál ceisteanna. Leis na comhpháirteanna seo i bhfeidhm, tá do shuíomh bheag na glaoch ar iomlán agus beo.",
        ),
        // Italian
        'it_IT' => array(
            'label' => "Sito web dedicato + Questionario",
            'desc'  => "Una piattaforma dedicata con il suo indirizzo unico forma la base dell'applicazione di segnalazione di Phoenix Whistleblowing. Puoi selezionare tre tipi di questionari. Con questi componenti in posizione, il tuo sito di segnalazione è completamente operativo e online.",
        ),
        // Latvian
        'lv_LV' => array(
            'label' => "Dedikētā tīmekļa vietne + aptauja",
            'desc'  => "Dedikētā platforma ar tās unikālo adresi veido Phoenix Whistleblowing lietojumprogrammas pamatu. Jūs varat izvēlēties no trim aptauju veidiem. Ar šiem komponentiem uz vietas jūsu paziņojumu vietne ir pilnībā darboties un tiešraides.",
        ),
        // Lithuanian
        'lt_LT' => array(
            'label' => "Atsidavusi svetainė + apklausa",
            'desc'  => "Atsidavusi platforma su unikaliu adresu yra Phoenix Whistleblowing programos pamatas. Galite pasirinkti iš trijų apklausų tipų. Turint šiuos komponentus, jūsų pranešimų svetainė veikia pilnai ir tiesiogiai.",
        ),
        // Maltese
        'mt_MT' => array(
            'label' => "Websajt Dedikat + Mistoqsijiet",
            'desc'  => "Piattaforma dedicata b'din l-indirizz uniku tifforma l-qoxra tal-applikazzjoni Phoenix Whistleblowing. Tista 'tagħżel minn tliet tipi ta' mistoqsijiet. B'dawn il-kumponenti f'idejk, is-sit tiegħek tal-ippjanar huwa operattiv sħiħ u attiv.",
        ),
        // Norwegian Nynorsk
        'nb_NO' => array(
            'label' => "Dedikert nettsted + Spørreskjema",
            'desc'  => "En dedikert plattform med sin unike adresse danner hjørnesteinen i Phoenix Whistleblowing-applikasjonen. Du kan velge blant tre typer spørreskjemaer. Med disse komponentene på plass, er nettstedet ditt for varsling fullt operativt og live.",
        ),
        // Polish
        'pl_PL' => array(
            'label' => "Dedykowana strona internetowa + Kwestionariusz",
            'desc'  => "Dedykowana platforma z unikalnym adresem stanowi fundament aplikacji Phoenix Whistleblowing. Możesz wybrać spośród trzech rodzajów kwestionariuszy. Dzięki tym komponentom twoja strona do zgłaszania nieprawidłowości jest w pełni operacyjna i aktywna.",
        ),
        // Portuguese
        'pt_PT' => array(
            'label' => "Site dedicado + Questionário",
            'desc'  => "Uma plataforma dedicada com seu endereço exclusivo forma a base do aplicativo de denúncias Phoenix. Você pode selecionar entre três tipos de questionários. Com esses componentes no lugar, seu site de denúncias está totalmente operacional e ativo.",
        ),
        // Romanian
        'ro_RO' => array(
            'label' => "Site web dedicat + Chestionar",
            'desc'  => "O platformă dedicată cu adresa sa unică formează piatra de temelie a aplicației Phoenix Whistleblowing. Puteți selecta din trei tipuri de chestionare. Cu aceste componente în loc, site-ul dvs. de denunțuri este complet operațional și funcțional.",
        ),
        // Slovak
        'sk_SK' => array(
            'label' => "Dedicovaný web + Dotazník",
            'desc'  => "Dedicovaná platforma s jejím jedinečným adresom tvorí základ aplikácie Phoenix Whistleblowing. Môžete si vybrať zo troch typov dotazníkov. S týmito komponentmi na mieste je váš web na oznamovanie verejnosti plne prevádzkyschopný a živý.",
        ),
        // Slovenian
        'sl_SI' => array(
            'label' => "Dodeljena spletna stran + Vprašalnik",
            'desc'  => "Dodeljena platforma s svojim edinstvenim naslovom tvori temelj aplikacije Phoenix Whistleblowing. Lahko izberete med tremi vrstami vprašalnikov. S temi komponentami na mestu je vaše spletno mesto za prijavljanje težav v celoti operativno in aktivno.",
        ),
        // Spanish
        'es_ES' => array(
            'label' => "Sitio web dedicado + Cuestionario",
            'desc'  => "Una plataforma dedicada con su dirección única forma la piedra angular de la aplicación de denuncia de Phoenix. Puedes seleccionar entre tres tipos de cuestionarios. Con estos componentes en su lugar, tu sitio de denuncia está completamente operativo y en vivo.",
        ),
        // Swedish
        'sv_SE' => array(
            'label' => "Dedikerad webbplats + Frågeformulär",
            'desc'  => "En dedikerad plattform med sin unika adress utgör hörnstenen i Phoenix Whistleblowing-applikationen. Du kan välja mellan tre typer av frågeformulär. Med dessa komponenter på plats är din whistleblowing-webbplats helt operativ och live.",
        ),
    ),
    /**
     * theme_library
     */
    'theme_library' => array(
        // English
        'en_US' => array( 
            'label' => "Access to Theme Library",
            'desc'  => "Access a diverse collection of over 30 dynamic themes designed for your dedicated website. Explore an extensive array of categories and discover themes rich in unique features, ensuring there's an ideal fit for your company or institution's identity.",
        ),
        // Basque
        'eu_ES' => array( 
            'label' => "Tema liburutegira sartzeko",
            'desc'  => "Webgune zehatzarentzako diseinatutako 30+ dinamiko tema baten aurrean sarbide eskuragarria izango duzu. Kategori eremuan zehar sakontasun handiko tailerik aurkitu eta alderdi bereziko eta berezitasun aberastuko tema bat aurki dezakezu, zure enpresaren edo erakundearen identitatearentzako ideal egokiena bermatzeko.",
        ),
        // Bulgarian
        'bg_BG' => array(
            'label' => "Достъп до библиотеката с теми",
            'desc'  => "Пристъпете до разнообразна колекция от над 30 динамични теми, проектирани за вашия специализиран уебсайт. Изследвайте обширен набор от категории и открийте теми, богати на уникални функции, което гарантира идеално попадение за идентичността на вашата компания или институция.",
        ),
        // Croatian
        'hr' => array(
            'label' => "Pristup tematskoj biblioteci",
            'desc'  => "Pristupite raznolikoj kolekciji od preko 30 dinamičnih tema dizajniranih za vašu posvećenu web stranicu. Istražite širok spektar kategorija i otkrijte teme bogate jedinstvenim značajkama, osiguravajući idealno prilagođavanje identitetu vaše tvrtke ili institucije.",
        ),
        // Czech
        'cs_CZ' => array(
            'label' => "Přístup k knihovně motivů",
            'desc'  => "Získejte přístup k rozmanité sbírce více než 30 dynamických témat navržených pro váš specializovaný web. Prozkoumejte rozsáhlou škálu kategorií a objevte témata bohatá na unikátní funkce, zajistěte si tak ideální variantu pro identitu vaší firmy nebo instituce.",
        ),
        // Danish
        'da_DK' => array(
            'label' => "Adgang til temabibliotek",
            'desc'  => "Få adgang til en mangfoldig samling af over 30 dynamiske temaer designet til dit dedikerede websted. Udforsk en bred vifte af kategorier, og opdag temaer, der er rige på unikke funktioner, hvilket sikrer, at der er en ideel pasform til din virksomhed eller institutions identitet.",
        ),
        // Dutch
        'nl_NL' => array(
            'label' => "Toegang tot Themabibliotheek",
            'desc'  => "Toegang tot een diverse collectie van meer dan 30 dynamische thema's ontworpen voor uw toegewijde website. Verken een uitgebreid scala aan categorieën en ontdek thema's die rijk zijn aan unieke functies, waardoor er een ideale match is voor de identiteit van uw bedrijf of instelling.",
        ),
        // Estonian
        'et' => array(
            'label' => "Juurdepääs teemaraamatukogule",
            'desc'  => "Pääsete juurde üle 30 dünaamilise teema mitmekülgsele kogumikule, mis on loodud teie pühendatud veebisaidile. Uurige ulatuslikku kategooriate valikut ja avastage teemasid, mis on rikkad ainulaadsete funktsioonide poolest, tagades, et teie ettevõttele või institutsioonile oleks ideaalne sobivus.",
        ),
        // Finnish
        'fi' => array(
            'label' => "Pääsy teemakirjastoon",
            'desc'  => "Pääsy monipuoliseen kokoelmaan yli 30 dynaamista teemaa, jotka on suunniteltu omistetulle verkkosivustollesi. Tutki laajaa valikoimaa kategorioita ja löydä teemoja, jotka ovat rikkaita ainutlaatuisia ominaisuuksia, varmistaen täydellisen sopivuuden yrityksesi tai oppilaitoksesi identiteetille.",
        ),
        // French
        'fr_FR' => array(
            'label' => "Accès à la bibliothèque de thèmes",
            'desc'  => "Accédez à une collection diversifiée de plus de 30 thèmes dynamiques conçus pour votre site web dédié. Explorez une vaste gamme de catégories et découvrez des thèmes riches en fonctionnalités uniques, garantissant qu'il existe un ajustement idéal pour l'identité de votre entreprise ou institution.",
        ),
        // German
        'de_DE' => array(
            'label' => "Zugang zur Themenbibliothek",
            'desc'  => "Greifen Sie auf eine vielfältige Sammlung von über 30 dynamischen Themen zu, die für Ihre dedizierte Website entwickelt wurden. Entdecken Sie eine umfangreiche Auswahl an Kategorien und finden Sie Themen, die reich an einzigartigen Funktionen sind, um sicherzustellen, dass es eine ideale Passform für die Identität Ihres Unternehmens oder Ihrer Institution gibt.",
        ),
        // Greek
        'el' => array(
            'label' => "Πρόσβαση στη Βιβλιοθήκη Θεμάτων",
            'desc'  => "Αποκτήστε πρόσβαση σε μια ποικιλία πάνω από 30 δυναμικών θεμάτων σχεδιασμένων για το αφιερωμένο σας ιστότοπο. Εξερευνήστε μια εκτεταμένη γκάμα κατηγοριών και ανακαλύψτε θέματα πλούσια σε μοναδικά χαρακτηριστικά, εξασφαλίζοντας ιδανική εφαρμογή για την ταυτότητα της εταιρείας ή ιδρύματός σας.",
        ),
        // Hungarian
        'hu_HU' => array(
            'label' => "Hozzáférés a Témakönyvtárhoz",
            'desc'  => "Férjen hozzá több mint 30 dinamikus témához, melyeket a dedikált webhelyére terveztek. Fedezze fel az egyik legkiterjedtebb kategóriák kínálatát, és találjon témákat, melyek gazdagok egyedi jellemzőkben, biztosítva a tökéletes illeszkedést vállalata vagy intézménye azonosságához.",
        ),
        // Irish
        'ga_IE' => array(
            'label' => "Rochtain ar Leabharlann na Téamaí",
            'desc'  => "Rochtain ar chnuasach éagsúil de bhreis agus 30 téama dinimiciúla atá deartha d'fhóram gréasáin dírithe. Taistealaigh tríd raon fairsinge de chatagóirí agus fáil amach faoi théamaí atá bogtha i gcuid de na gnéithe uathúla, ag chinntiú go bhfuil idéalú foirfe ann do shaolré an chomhlachta nó an institiúid.",
        ),
        // Italian
        'it_IT' => array(
            'label' => "Accesso alla Libreria dei Temi",
            'desc'  => "Accedi a una vasta collezione di oltre 30 temi dinamici progettati per il tuo sito web dedicato. Esplora un'ampia gamma di categorie e scopri temi ricchi di funzionalità uniche, garantendo che ci sia una corrispondenza ideale con l'identità della tua azienda o istituzione.",
        ),
        // Latvian
        'lv_LV' => array(
            'label' => "Piekļuve Tēmu bibliotēkai",
            'desc'  => "Pieejiet dažādai kolekcijai ar vairāk nekā 30 dinamiskiem dizainiem, kas izstrādāti jūsu veltītajai vietnei. Izmēģiniet plašu kategoriju klāstu un atklājiet tematus, kas bagāti ar unikālām funkcijām, nodrošinot, ka jūsu ļmonēs atrodami.",
        ),
        // Lithuanian
        'lt_LT' => array(
            'label' => "Prieiga prie temų bibliotekos",
            'desc'  => "Prieiga prie daugiau nei 30 dinaminių temų, skirtų jūsų skirtam tinklalapiui. Ištyrus platus kategorijų spektrą, galėsite rasti temų, turtingų unikaliomis funkcijomis, užtikrinančiomis, kad jūsų įmonės ar institucijos tapatybei būtų idealiai pritaikyta.",
        ),
        // Maltese
        'mt_MT' => array(
            'label' => "Aċċess għall-Librerija ta' Temi",
            'desc'  => "Aċċess għal kollezzjoni diversifikata ta' aktar minn 30 temi dinamiċi mfassla għal websajt dedikat tiegħek. Iesplora matrice estensiva ta' kategoriji u skopri temi bogħda f'karatteristiċi unika, li jissiguraw li hemm fitt ideali għal identità kumpanija jew istituzzjoni tiegħek.",
        ),
        // Norwegian
        'nb_NO' => array(
            'label' => "Tilgang til Temabiblioteket",
            'desc'  => "Få tilgang til en variert samling av over 30 dynamiske temaer designet for din dedikerte nettside. Utforsk et omfattende utvalg av kategorier og oppdag temaa",
        ),
        // Polish
        'pl_PL' => array(
            'label' => "Dostęp do Biblioteki Motywów",
            'desc'  => "Dostęp do różnorodnej kolekcji ponad 30 dynamicznych motywów zaprojektowanych dla Twojej dedykowanej strony internetowej. Przejrzyj obszerny wybór kategorii i odkryj motywy bogate w unikalne funkcje, zapewniając idealne dopasowanie do tożsamości Twojej firmy lub instytucji.",
        ),
        // Portuguese
        'pt_PT' => array(
            'label' => "Acesso à Biblioteca de Temas",
            'desc'  => "Acesse uma coleção diversificada de mais de 30 temas dinâmicos projetados para o seu site dedicado. Explore uma ampla variedade de categorias e descubra temas ricos em recursos exclusivos, garantindo um ajuste ideal para a identidade de sua empresa ou instituição.",
        ),
        // Romanian
        'ro_RO' => array(
            'label' => "Acces la Biblioteca de Tematici",
            'desc'  => "Accesați o colecție diversificată de peste 30 de teme dinamice proiectate pentru site-ul dvs. dedicat. Explorați o gamă extinsă de categorii și descoperiți teme bogate în caracteristici unice, asigurându-vă că există o potrivire ideală pentru identitatea companiei sau instituției dvs.",
        ),
        // Slovak
        'sk_SK' => array(
            'label' => "Prístup k knižnici motívov",
            'desc'  => "Získajte prístup k rôznorodej kolekcii viac ako 30 dynamických tém navrhnutých pre váš venovaný webový portál. Preskúmajte rozsiahlu škálu kategórií a objavte témy bohaté na jedinečné funkcie, aby ste sa uistili, že existuje ideálne riešenie pre identitu vašej spoločnosti alebo inštitúcie.",
        ),
        // Slovenian
        'sl_SI' => array(
            'label' => "Dostop do knjižnice tem",
            'desc'  => "Dostop do raznolike zbirke več kot 30 dinamičnih tem, zasnovanih za vašo posebno spletno stran. Raziščite obsežen nabor kategorij in odkrijte teme, bogate z edinstvenimi funkcijami, da se zagotovi idealno prileganje identiteti vašega podjetja ali institucije.",
        ),
        // Spanish
        'es_ES' => array(
            'label' => "Acceso a la Biblioteca de Temas",
            'desc'  => "Acceda a una colección diversa de más de 30 temas dinámicos diseñados para su sitio web dedicado. Explore una amplia gama de categorías y descubra temas ricos en características únicas, asegurando que haya un ajuste ideal para la identidad de su empresa o institución.",
        ),
        // Swedish
        'sv_SE' => array(
            'label' => "Åtkomst till Temabiblioteket",
            'desc'  => "Få tillgång till en mångsidig samling av över 30 dynamiska teman som är utformade för din dedikerade webbplats. Utforska ett omfattande utbud av kategorier och upptäck teman rika på unika funktioner, vilket säkerställer att det finns en idealisk passform för din företags eller institutions identitet.",
        ),
    ),
    /**
     * theme_whitelabel*
     */
    'theme_whitelabel' => array(
        // English
        'en_US' => array(
            'label' => "White Label Theme",
            'desc'  => "Gain exclusive access to our \"white-labeled\" themes, developed for seamless customization and effortless rebranding to suit your unique requirements. These pre-designed themes offer unparalleled versatility and adaptability, ensuring your brand identity remains front and center.",
        ),
        // Basque
        'eu_ES' => array(
            'label' => "Zuri-etiketako tema",
            'desc'  => "Iraganeko irisgarritasun eta erabilera aldakorra eta erabiltzailearena egokitzen diren \"zuri-etiketatutako\" gure temak irakurri. Aukeraturiko temak antolakuntza eta birmarkaketa errazak eskaintzen dituzte, zure eskaerak betetzeko. Horiek baino ez dute antzekotasunik edo moldagarritasunik eskaintzen, marka identitatea aurrean eta erdian mantentzen dute.",
        ),
        // Bulgarian
        'bg_BG' => array(
            'label' => "Тема с бяла етикета",
            'desc'  => "Получете изключителен достъп до нашите теми с \"бели етикети\", разработени за безупречно персонализиране и лесно променяне на марката си, за да отговарят на вашите уникални изисквания. Тези предварително разработени теми предлагат безпрецедентна гъвкавост и приспособяемост, гарантирайки, че вашата маркова идентичност остава на преден план.",
        ),
        // Croatian
        'hr' => array(
            'label' => "Tema s bijelim etiketama",
            'desc'  => "Osigurajte ekskluzivan pristup našim temama \"bijelog etiketa\", razvijenim za besprijekornu prilagodbu i lako preoblikovanje kako bi odgovarali vašim jedinstvenim zahtjevima. Ove unaprijed dizajnirane teme nude neusporedivu svestranost i prilagodljivost, osiguravajući da vaš identitet marke ostane u središtu pozornosti.",
        ),
        // Czech
        'cs_CZ' => array(
            'label' => "Motiv White Label",
            'desc'  => "Získání exkluzivního přístupu k našim \"white-labeled\" motivům, vyvinutých pro bezproblémové přizpůsobení a snadné přebrandování podle vašich jedinečných požadavků. Tyto předem navržené motivy nabízejí nepřekonatelnou všestrannost a přizpůsobivost, což zajišťuje, že vaše značka zůstává v popředí.",
        ),
        // Danish
        'da_DK' => array(
            'label' => "White Label-tema",
            'desc'  => "Få eksklusiv adgang til vores \"hvidmærkede\" temaer, udviklet til problemfri tilpasning og ubesværet rebranding for at passe til dine unikke krav. Disse prædesignede temaer tilbyder uovertruffen alsidighed og tilpasningsevne, hvilket sikrer, at din brandidentitet forbliver i centrum.",
        ),
        // Dutch
        'nl_NL' => array(
            'label' => "White Label Thema",
            'desc'  => "Verkrijg exclusieve toegang tot onze \"white-labeled\" thema's, ontwikkeld voor naadloze aanpassing en moeiteloze rebranding om aan uw unieke vereisten te voldoen. Deze vooraf ontworpen thema's bieden ongeëvenaarde veelzijdigheid en aanpasbaarheid, zodat uw merkidentiteit centraal blijft staan.",
        ),
        // Estonian
        'et' => array(
            'label' => "Valge sildi teema",
            'desc'  => "Saate eksklusiivse juurdepääsu meie \"valgetele sildistatud\" teemadele, mis on välja töötatud sujuvaks kohandamiseks ja hõlpsaks ümberbrändimiseks vastavalt teie ainulaadsetele nõuetele. Need eelnevalt kujundatud teemad pakuvad enneolematut mitmekülgsust ja kohanduvust, tagades, et teie brändi identiteet jääb esiplaanile.",
        ),
        // Finnish
        'fi' => array(
            'label' => "Valkomerkitty teema",
            'desc'  => "Hanki yksinoikeudellinen pääsy \"valkomerkittyihin\" teemoihimme, jotka on kehitetty saumattomaan mukauttamiseen ja vaivattomaan uudelleenbrändäykseen vastaamaan ainutlaatuisia tarpeitasi. Nämä valmiiksi suunnitellut teemat tarjoavat vertaansa vailla olevaa monipuolisuutta ja sopeutumiskykyä, varmistaen, että brändi-identiteettisi pysyy etualalla.",
        ),
        // French
        'fr_FR' => array(
            'label' => "Thème White Label",
            'desc'  => "Bénéficiez d'un accès exclusif à nos thèmes \"white-labeled\", développés pour une personnalisation transparente et un rebranding sans effort afin de répondre à vos besoins uniques. Ces thèmes pré-conçus offrent une polyvalence et une adaptabilité incomparables, garantissant que l'identité de votre marque reste au premier plan.",
        ),
        // German
        'de_DE' => array(
            'label' => "White-Label-Thema",
            'desc'  => "Erhalten Sie exklusiven Zugang zu unseren \"white-labeled\" Themen, entwickelt für nahtlose Anpassung und mühelose Neugestaltung, um Ihren einzigartigen Anforderungen gerecht zu werden. Diese vordefinierten Themen bieten eine beispiellose Vielseitigkeit und Anpassungsfähigkeit und gewährleisten, dass Ihre Markenidentität im Vordergrund bleibt.",
        ),
        // Greek
        'el' => array(
            'label' => "Θέμα Με Λευκή Ετικέτα",
            'desc'  => "Αποκτήστε αποκλειστική πρόσβαση στα θέματά μας \"με λευκή ετικέτα\", που αναπτύχθηκαν για άψογη προσαρμογή και αναδιάρθρωση, προκειμένου να ικανοποιήσουν τις μοναδικές σας απαιτήσεις. Αυτά τα προ-σχεδιασμένα θέματα προσφέρουν ασύγκριτη ευελιξία και προσαρμοστικότητα, διασφαλίζοντας ότι η ταυτότητα της εταιρείας σας παραμένει στο προσκήνιο.",
        ),
        // Hungarian
        'hu_HU' => array(
            'label' => "Fehér Címke Téma",
            'desc'  => "Saját \"fehér címkés\" témáink exkluzív hozzáférése, melyeket zökkenőmentesen testreszabtunk és könnyen újratervezhetünk, hogy egyedülálló igényeihez igazítsuk. Ezek az előre tervezett témák páratlan rugalmasságot és alkalmazkodóképességet kínálnak, biztosítva, hogy a márkaidentitása középpontban maradjon.",
        ),
        // Irish
        'ga_IE' => array(
            'label' => "Téama Bán-Chlúdaithe",
            'desc'  => "Bain rochtain ar leith ar ár mbileoga \"bán-chlúdaithe\", atá forbartha chun idirdhealú gan stró ar fheidhmiúlacht agus athbhrandáil neamhbhrabúis chun freastal ar do riachtanais uathúla. Tairgeann na téamaí réamhthairgthe seo solúbthacht agus fáiltiúlacht gan chomóradh, ag cinntiú go bhfanann d'ainm branda lárnach.",
        ),
        // Italian
        'it_IT' => array(
            'label' => "Tema White Label",
            'desc'  => "Ottieni un accesso esclusivo ai nostri temi \"white-label\", sviluppati per una personalizzazione senza soluzione di continuità e un rebranding senza sforzo per soddisfare le tue esigenze uniche. Questi temi predefiniti offrono una versatilità e adattabilità senza pari, garantendo che l'identità del tuo marchio rimanga al centro dell'attenzione.",
        ),
        // Latvian
        'lv_LV' => array(
            'label' => "Baltā etiķete Tēma",
            'desc'  => "Iegūstiet ekskluzīvu piekļuvi mūsu \"white-labeled\" tēmiem, kas izstrādāti, lai nodrošinātu bezproblēmu pielāgojamību un vienkāršu rebranding, lai atbilstu jūsu unikālajām prasībām. Šie iepriekš izstrādātie tēmi piedāvā bezkonkurences versatilitāti un pielāgojamību, nodrošinot, ka jūsu zīmola identitāte paliek priekšplānā.",
        ),
        // Lithuanian
        'lt_LT' => array(
            'label' => "Baltos etiketės tema",
            'desc'  => "Gaukite išskirtinę prieigą prie mūsų \"white-labeled\" temų, sukurtų sklandžiam pritaikymui ir lengvam perbrandingui, atitinkant jūsų unikalias reikalavimus. Šios iš anksto sukurtos temos siūlo beprecedentę universalumą ir pritaikomumą, užtikrinant, kad jūsų prekės ženklas lieka priekyje ir centre.",
        ),
        // Maltese
        'mt_MT' => array(
            'label' => "Tema White Label",
            'desc'  => "Ikseb aċċess esklussiv għall-temi tagħna \"white-labeled\", żviluppati għal personalizzazzjoni bla ħsara u ribrandar sbieħ biex jilħqu l-ħtiġijiet uniki tiegħek. Dawn it-temi pre-iddisinnati joffru versatilità u adattabbiltà bla pari, jiżguraw li l-identità tal-marka tiegħek tinżel il-quddiem.",
        ),
        // Norwegian
        'nb_NO' => array(
            'label' => "White Label-tema",
            'desc'  => "Få eksklusiv tilgang til våre \"white-labeled\" temaer, utviklet for sømløs tilpasning og enkel ombranding for å tilpasse dine unike behov. Disse forhåndsdesignede temaene tilbyr enestående allsidighet og tilpasningsevne, og sikrer at din merkeidentitet forblir i fokus.",
        ),
        // Polish
        'pl_PL' => array(
            'label' => "Motyw White Label",
            'desc'  => "Zdobądź wyłączny dostęp do naszych motywów „white-labeled”, opracowanych w celu bezproblemowej dostosowywalności i łatwego rebrandingu, aby spełnić Twoje unikalne wymagania. Te wstępnie zaprojektowane motywy oferują niezrównaną wszechstronność i adaptacyjność, zapewniając, że tożsamość Twojej marki pozostaje na pierwszym planie.",
        ),
        // Portuguese
        'pt_PT' => array(
            'label' => "Tema de Etiqueta Branca",
            'desc'  => "Obtenha acesso exclusivo aos nossos temas \"white-labeled\", desenvolvidos para personalização contínua e rebranding sem esforço para atender às suas necessidades exclusivas. Esses temas pré-projetados oferecem uma versatilidade e adaptabilidade incomparáveis, garantindo que a identidade da sua marca permaneça em destaque.",
        ),
        // Romanian
        'ro_RO' => array(
            'label' => "Tema Etichetă Albă",
            'desc'  => "Obțineți acces exclusiv la temele noastre \"white-labeled\", dezvoltate pentru personalizare fără probleme și rebranding fără efort, pentru a se potrivi cerințelor unice ale dvs. Aceste teme pre-proiectate oferă o versatilitate și adaptabilitate fără egal, asigurând că identitatea mărcii dvs. rămâne în centrul atenției.",
        ),
        // Slovak
        'sk_SK' => array(
            'label' => "Motív s bielym štítkom",
            'desc'  => "Získajte exkluzívny prístup k našim motívom \"white-labeled\", vyvinutým pre bezproblémové prispôsobenie a jednoduché prebrandovanie, aby vyhovovali vašim jedinečným požiadavkám. Tieto preddefinované motívy ponúkajú bezkonkurenčnú všestrannosť a prispôsobivosť, zabezpečujúc, že vaša identita značky zostáva v popredí.",
        ),
        // Slovenian
        'sl_SI' => array(
            'label' => "Tema z belo oznako",
            'desc'  => "Pridobite ekskluziven dostop do naših tem \"white-labeled\", razvitih za brezhibno prilagajanje in enostavno preimenovanje, da ustrezajo vašim edinstvenim zahtevam. Ti predhodno oblikovani temi ponujata neprekosljivo vsestranskost in prilagodljivost ter zagotavljata, da ostane vaša blagovna identiteta v ospredju.",
        ),
        // Spanish
        'es_ES' => array(
            'label' => "Tema de Etiqueta Blanca",
            'desc'  => "Obtén acceso exclusivo a nuestros temas \"white-labeled\", desarrollados para una personalización perfecta y un rebranding sin esfuerzo para adaptarse a tus requisitos únicos. Estos temas pre-diseñados ofrecen una versatilidad y adaptabilidad sin igual, asegurando que la identidad de tu marca permanezca en primer plano.",
        ),
        // Swedish
        'sv_SE' => array(
            'label' => "Tema för Vit Etikett",
            'desc'  => "Få exklusiv åtkomst till våra \"white-labeled\" teman, utvecklade för sömlös anpassning och enkel omvarumärkning för att passa dina unika behov. Dessa fördesignade teman erbjuder enastående mångsidighet och anpassningsbarhet, vilket säkerställer att din varumärkesidentitet förblir i centrum.",
        ),
    ),
    /**
     * Language*
     */
    'language' => array(
        // English
        'en_US' => array(
            'label' => "Language & Localisation",
            'desc'  => "With a choice between more than 50+ languages, Phoenix Whistleblowing Software ensures accessibility for all employees and potential whistleblowers, regardless of their native language or proficiency. This inclusivity encourages reporting and effectively addresses language barriers, making it ideal for global organizations.",
        ),
        // Basque
        'eu_ES' => array(
            'label' => "Hizkuntza eta Lokalizazioa",
            'desc'  => "50+ hizkuntza aukeratu ahal izanez, Phoenix Whistleblowing Software-ak langile guztientzako eta aholkularientzako eskura bermatzen du, haien jatorrizko hizkuntzaren edo mailako mailaren arabera. Inkusibotasun horrek txostenak bultzatzen ditu eta hizkuntza oztopoak eraginkortasunez aurreztatzen ditu, nazioarteko erakundetarako ideala bihurtuz.",
        ),
        // Bulgarian
        'bg_BG' => array(
            'label' => "Език и локализация",
            'desc'  => "С избор между повече от 50+ езика, Phoenix Whistleblowing Software гарантира достъпност за всички служители и потенциални уведомители, независимо от техния роден език или владеене. Тази инклузивност стимулира докладването и ефективно се справя с езиковите бариери, което го прави идеално за глобални организации.",
        ),
        // Croatian
        'hr' => array(
            'label' => "Jezik i lokalizacija",
            'desc'  => "Odabirom između više od 50 jezika, Phoenix Whistleblowing Software osigurava dostupnost za sve zaposlenike i potencijalne uzbunjivače, bez obzira na njihov materinji jezik ili stručnost. Ova inkluzivnost potiče prijavljivanje i učinkovito rješava jezične barijere, čineći ga idealnim za globalne organizacije.",
        ),
        // Czech
        'cs_CZ' => array(
            'label' => "Jazyk a Lokalizace",
            'desc'  => "S výběrem mezi více než 50+ jazyky zajišťuje software Phoenix Whistleblowing přístupnost pro všechny zaměstnance a potenciální whistleblower, bez ohledu na jejich mateřský jazyk nebo jeho znalost. Tato inkluzivita podporuje hlášení a efektivně řeší jazykové bariéry, což je ideální pro globální organizace.",
        ),
        // Danish
        'da_DK' => array(
            'label' => "Sprog & lokalisering",
            'desc'  => "Med et valg mellem mere end 50+ sprog sikrer Phoenix Whistleblowing Software tilgængelighed for alle medarbejdere og potentielle whistleblowere, uanset deres modersmål eller færdigheder. Denne inklusivitet tilskynder til rapportering og adresserer effektivt sprogbarrierer, hvilket gør den ideel til globale organisationer.",
        ),
        // Dutch
        'nl_NL' => array(
            'label' => "Taal & Lokalisatie",
            'desc'  => "Met een keuze uit meer dan 50+ talen zorgt Phoenix Whistleblowing Software voor toegankelijkheid voor alle werknemers en potentiële klokkenluiders, ongeacht hun moedertaal of vaardigheid. Deze inclusiviteit moedigt rapportage aan en adresseert effectief taalbarrières, waardoor het ideaal is voor wereldwijde organisaties.",
        ),
        // Estonian
        'et' => array(
            'label' => "Keele ja lokaliseerimise",
            'desc'  => "Valikuga üle 50 keele tagab Phoenix Whistleblowing Software ligipääsetavuse kõigile töötajatele ja potentsiaalsetele whistleboweritele, sõltumata nende emakeelest või oskustasemest. See kaasatus julgustab aruandlust ja lahendab tõhusalt keelebarjääre, muutes selle ideaalseks globaalsetele organisatsioonidele.",
        ),
        // Finnish
        'fi' => array(
            'label' => "Kieli & Lokalisointi",
            'desc'  => "Valitsemalla yli 50+ kielen välillä Phoenix Whistleblowing -ohjelmisto varmistaa saavutettavuuden kaikille työntekijöille ja mahdollisille ilmiantajille riippumatta heidän äidinkielestään tai taidoistaan. Tämä inklusiivisuus kannustaa raportointiin ja käsittelee tehokkaasti kielimuureja, mikä tekee siitä ihanteellisen globaaleille organisaatioille.",
        ),
        // French
        'fr_FR' => array(
            'label' => "Langue & Localisation",
            'desc'  => "Avec un choix entre plus de 50 langues, le logiciel de signalement Phoenix garantit l'accessibilité à tous les employés et aux éventuels lanceurs d'alerte, quel que soit leur langue maternelle ou leur niveau de compétence. Cette inclusivité encourage le signalement et adresse efficacement les barrières linguistiques, en en faisant l'outil idéal pour les organisations mondiales.",
        ),
        // German
        'de_DE' => array(
            'label' => "Sprache & Lokalisierung",
            'desc'  => "Mit der Auswahl aus mehr als 50 Sprachen gewährleistet die Phoenix-Whistleblowing-Software die Zugänglichkeit für alle Mitarbeiter und potenziellen Hinweisgeber, unabhängig von ihrer Muttersprache oder Kompetenz. Diese Inklusivität ermutigt zur Meldung und adressiert effektiv Sprachbarrieren, was sie ideal für globale Organisationen macht.",
        ),
        // Greek
        'el' => array(
            'label' => "Γλώσσα & Τοπικοποίηση",
            'desc'  => "Με επιλογή ανάμεσα σε περισσότερες από 50 γλώσσες, το Phoenix Whistleblowing Software εξασφαλίζει πρόσβαση για όλους τους εργαζομένους και τους πιθανούς αποκαλυπτήριους, ανεξάρτητα από τη μητρική τους γλώσσα ή επάρκειά τους. Αυτή η ενσωμάτωση προάγει την αναφορά και αντιμετωπίζει αποτελεσματικά τα γλωσσικά εμπόδια, κάνοντάς το ιδανικό για παγκόσμιες οργανώσεις.",
        ),
        // Hungarian
        'hu_HU' => array(
            'label' => "Nyelv és lokalizáció",
            'desc'  => "Több mint 50 nyelv közül választhatva a Phoenix Whistleblowing szoftver biztosítja az elérhetőséget minden alkalmazott és potenciális jelentő számára, függetlenül az anyanyelvüktől vagy jártasságuktól. Ez az inkluzivitás ösztönzi a jelentést és hatékonyan kezeli a nyelvi akadályokat, így ideális globális szervezetek számára.",
        ),
        // Irish
        'ga_IE' => array(
            'label' => "Teanga agus Áitiúlacht",
            'desc'  => "Le rogha idir breis agus 50+ teanga, déanann Bogearraí Fuascailt Fénix ​​a chinntiú go bhfuil rochtain ag gach fostaithe agus cuirfidh na héilimh leis, gan aird ar a dteanga dúchais nó ar a gcuid scileanna. Cuireann an inklúdachas seo le héilimh a dhéanamh agus déanann sé dul i ngleic go héifeachtach le barraí teanga, rud a dhéanann é a dhéanamh iontach oiriúnach do chomhlachtaí domhanda.",
        ),
        // Italian
        'it_IT' => array(
            'label' => "Lingua e localizzazione",
            'desc'  => "Con una scelta tra più di 50 lingue, il software di segnalazione Phoenix garantisce l'accessibilità a tutti i dipendenti e potenziali whistleblower, indipendentemente dalla loro lingua madre o competenza. Questa inclusività incoraggia la segnalazione e affronta efficacemente le barriere linguistiche, rendendolo ideale per le organizzazioni globali.",
        ),
        // Latvian
        'lv_LV' => array(
            'label' => "Valoda un lokalizācija",
            'desc'  => "Ar izvēli starp vairāk nekā 50+ valodām, \"Phoenix Whistleblowing Software\" nodrošina pieejamību visiem darbiniekiem un potenciālajiem iesūtītājiem neatkarīgi no viņu dzimtās valodas vai prasmēm. Šāda iekļautība veicina ziņošanu un efektīvi risina valodu barjeras, padarot to par ideālu globālām organizācijām.",
        ),
        // Lithuanian
        'lt_LT' => array(
            'label' => "Kalba ir Lokalizacija",
            'desc'  => "Pasirinkus iš daugiau nei 50+ kalbų, „Phoenix Whistleblowing Software“ užtikrina prieinamumą visiems darbuotojams ir potencialiems pranešėjams, nepriklausomai nuo jų gimtosios kalbos ar kompetencijos. Ši įtrauktis skatina pranešimų pateikimą ir veiksmingai sprendžia kalbinius barjerus, todėl tai yra idealu pasaulinėms organizacijoms.",
        ),
        // Maltese
        'mt_MT' => array(
            'label' => "Lingwa u Lokalizzazzjoni",
            'desc'  => "B'għażla bejn aktar minn 50+ lingwa, il-Software ta' Phoenix Whistleblowing tassigura aċċessibilità għal kollha l-impjegati u l-potenzjali Whistleblowers, ħlief mill-lingwa nattiva jew mill-kompetenza tagħhom. Din l-inkluzzività tirringrazzja l-irrapportar u tindirizza b'mod effettiv il-barreri tal-lingwa, li jagħmelha ideali għal organizzazzjonijiet globali.",
        ),
        // Norwegian
        'nb_NO' => array(
            'label' => "Språk og lokalisering",
            'desc'  => "Med et valg mellom mer enn 50+ språk, sikrer Phoenix Whistleblowing Software tilgjengelighet for alle ansatte og potensielle varslere, uavhengig av deres morsmål eller ferdigheter. Denne inkluderingen oppfordrer til rapportering og adresserer effektivt språkbarrierer, noe som gjør den ideell for globale organisasjoner.",
        ),
        // Polish
        'pl_PL' => array(
            'label' => "Język i lokalizacja",
            'desc'  => "Dzięki wyborowi spośród ponad 50+ języków, oprogramowanie Phoenix Whistleblowing zapewnia dostępność dla wszystkich pracowników i potencjalnych informatorów, niezależnie od ich języka ojczystego lub biegłości. Ta włączność zachęca do raportowania i skutecznie eliminuje bariery językowe, co czyni je idealnym dla globalnych organizacji.",
        ),
        // Portuguese
        'pt_PT' => array(
            'label' => "Idioma e Localização",
            'desc'  => "Com uma escolha entre mais de 50+ idiomas, o Software de Denúncia Phoenix garante acessibilidade para todos os funcionários e potenciais denunciantes, independentemente de seu idioma nativo ou proficiência. Essa inclusão incentiva a denúncia e aborda efetivamente as barreiras linguísticas, tornando-o ideal para organizações globais.",
        ),
        // Romanian
        'ro_RO' => array(
            'label' => "Limbă și Localizare",
            'desc'  => "Cu o alegere între mai mult de 50 de limbi, Software-ul de denunțare Phoenix asigură accesibilitate pentru toți angajații și potențialii denunțători, indiferent de limba maternă sau competență. Această incluziune încurajează raportarea și abordează eficient barierele lingvistice, făcându-l ideal pentru organizațiile globale.",
        ),
        // Slovak
        'sk_SK' => array(
            'label' => "Jazyk a Lokalizácia",
            'desc'  => "S výberom medzi viac ako 50+ jazykmi zabezpečuje softvér na odhaľovanie korupcie Phoenix dostupnosť pre všetkých zamestnancov a potenciálnych oznamovateľov, bez ohľadu na ich materinský jazyk alebo úroveň odbornosti. Táto inkluzivita podnecuje oznamovanie a efektívne rieši jazykové bariéry, čo ho robí ideálnym pre globálne organizácie.",
        ),
        // Slovenian
        'sl_SI' => array(
            'label' => "Jezik in lokalizacija",
            'desc'  => "Z izbiro med več kot 50+ jeziki Phoenix Whistleblowing Software zagotavlja dostopnost za vse zaposlene in morebitne razkrinkovalce, ne glede na njihov materni jezik ali strokovnost. Ta vključenost spodbuja poročanje in učinkovito rešuje jezikovne ovire, kar ga naredi idealnega za globalne organizacije.",
        ),
        // Spanish
        'es_ES' => array(
            'label' => "Idioma y Localización",
            'desc'  => "Con una selección entre más de 50+ idiomas, el software de denuncia de irregularidades de Phoenix garantiza accesibilidad para todos los empleados y posibles denunciantes, independientemente de su idioma nativo o habilidades. Esta inclusividad fomenta la presentación de informes y aborda eficazmente las barreras lingüísticas, lo que lo hace ideal para organizaciones globales.",
        ),
        // Swedish
        'sv_SE' => array(
            'label' => "Språk och Lokalisering",
            'desc'  => "Med ett val mellan mer än 50+ språk säkerställer Phoenix Whistleblowing Software tillgänglighet för alla anställda och potentiella visselblåsare, oavsett deras modersmål eller kompetens. Denna inkludering uppmuntrar rapportering och adresser effektivt språkhinder, vilket gör det idealiskt för globala organisationer.",
        ),
    ),
    /**
     * Mobile App*
     */
    'mobile_app' => array(
        // English
        'en_US' => array(
            'label' => "Mobile App",
            'desc'  => "Add the Phoenix Whistleblowing mobile app into your array of channels, accessible on both Android and iOS platforms. With smartphones being an integral part of daily life for many, the convenience of this app empowers individuals to swiftly report concerns anytime, anywhere, fostering a culture of transparency and accountability.",
        ),
        // Basque
        'eu_ES' => array(
            'label' => "Mugikor Aplikazioa",
            'desc'  => "Phoenix Whistleblowing aplikazioaren mugikor aplikazioa kanal multzoan gehitu, Android eta iOS plataforma bietan eskuragarria. Smartfonoak eguneroko bizitzaren zati integratzen direnez, aplikazio honek pertsonak gauzak azkar eta gertu iragartzen ditu, edonon eta edozein momentutan, gardentasun eta kontu-kontu kultura bultzatuz.",
        ),
        // Bulgarian
        'bg_BG' => array(
            'label' => "Мобилно приложение",
            'desc'  => "Добавете мобилното приложение за сигнализиране на нарушения в „Финикс“ в масива си от канали, достъпен на Android и iOS платформи. Тъй като смартфоните са неотделима част от ежедневието на много хора, удобството на това приложение позволява на индивидите бързо да докладват за притеснения по всяко време и навсякъде, като се подпомага културата на прозрачност и отговорност.",
        ),
        // Croatian
        'hr' => array(
            'label' => "Mobilna aplikacija",
            'desc'  => "Dodajte mobilnu aplikaciju za prijavu nepravilnosti Phoenix Whistleblowing u svoj niz kanala, dostupnu na Android i iOS platformama. Budući da su pametni telefoni neizostavan dio svakodnevnog života mnogih ljudi, praktičnost ove aplikacije omogućuje pojedincima da brzo prijave probleme bilo kada i bilo gdje, potičući kulturu transparentnosti i odgovornosti.",
        ),
        // Czech
        'cs_CZ' => array(
            'label' => "Mobilní aplikace",
            'desc'  => "Přidejte mobilní aplikaci Phoenix Whistleblowing do vaší škály kanálů, dostupných na obou platformách Android a iOS. Vzhledem k tomu, že chytré telefony jsou pro mnoho lidí nedílnou součástí každodenního života, pohodlí této aplikace umožňuje jednotlivcům rychle hlásit problémy kdykoli a kdekoli a posiluje tak kulturu transparentnosti a odpovědnosti.",
        ),
        // Danish
        'da_DK' => array(
            'label' => "Mobil App",
            'desc'  => "Tilføj Phoenix Whistleblowing-mobilappen til dit udvalg af kanaler, der er tilgængeligt på både Android- og iOS-platforme. Da smartphones er en integreret del af hverdagen for mange, giver denne app brugerens bekvemmelighed mulighed for hurtigt at rapportere bekymringer når som helst og hvor som helst, hvilket fremmer en kultur af gennemsigtighed og ansvarlighed.",
        ),
        // Dutch
        'nl_NL' => array(
            'label' => "Mobiele App",
            'desc'  => "Voeg de Phoenix Klokkenluiders mobiele app toe aan uw scala aan kanalen, toegankelijk op zowel Android- als iOS-platforms. Aangezien smartphones een integraal onderdeel zijn van het dagelijks leven voor velen, stelt het gemak van deze app individuen in staat om snel zorgen te melden, altijd en overal, wat een cultuur van transparantie en verantwoording bevordert.",
        ),
        // Estonian
        'et' => array(
            'label' => "Mobiilirakendus",
            'desc'  => "Lisage Phoenixi Whistleblowing mobiilirakendus oma kanalite valikusse, mis on kättesaadavad nii Androidi kui ka iOS-i platvormidel. Kuna nutitelefonid on paljude inimeste igapäevaelu lahutamatu osa, võimaldab selle rakenduse mugavus inimestel kiiresti esitada muret igal ajal ja igal pool ning soodustada läbipaistvuse ja vastutuse kultuuri.",
        ),
        // Finnish
        'fi' => array(
            'label' => "Mobiilisovellus",
            'desc'  => "Lisää Phoenixin ilmiantomobiilisovellus valikoimaasi kanavia, saatavilla sekä Android- että iOS-alustoilla. Kun älypuhelimet ovat monille olennainen osa päivittäistä elämää, tämän sovelluksen kätevyys antaa yksilöille mahdollisuuden ilmoittaa huolistaan nopeasti milloin tahansa ja missä tahansa, edistäen avoimuuden ja vastuullisuuden kulttuuria.",
        ),
        // French
        'fr_FR' => array(
            'label' => "Application mobile",
            'desc'  => "Ajoutez l'application mobile de dénonciation de Phoenix à votre gamme de canaux, accessible sur les plates-formes Android et iOS. Les smartphones étant une partie intégrante de la vie quotidienne pour beaucoup, la commodité de cette application permet aux individus de signaler rapidement des problèmes à tout moment et en tout lieu, favorisant une culture de transparence et de responsabilité.",
        ),
        // German
        'de_DE' => array(
            'label' => "Mobile App",
            'desc'  => "Fügen Sie die mobile Phoenix Whistleblowing-App Ihrem Angebot an Kanälen hinzu, die auf Android- und iOS-Plattformen zugänglich sind. Da Smartphones für viele Menschen ein integraler Bestandteil des täglichen Lebens sind, ermöglicht die Bequemlichkeit dieser App Einzelpersonen, Bedenken jederzeit und überall schnell zu melden und fördert so eine Kultur der Transparenz und Rechenschaftspflicht.",
        ),
        // Greek
        'el' => array(
            'label' => "Κινητή Εφαρμογή",
            'desc'  => "Προσθέστε την κινητή εφαρμογή Phoenix Whistleblowing στον πίνακα καναλιών σας, προσβάσιμη σε και τις δύο πλατφόρμες Android και iOS. Με τα smartphones να είναι ουσιαστικό μέρος της καθημερινής ζωής για πολλούς, η ευκολία αυτής της εφαρμογής επιτρέπει στα άτομα να αναφέρουν γρήγορα τις ανησυχίες τους οποιαδήποτε στιγμή, οπουδήποτε, προωθώντας μια κουλτούρα διαφάνειας και ευθύνης.",
        ),
        // Hungarian
        'hu_HU' => array(
            'label' => "Mobilalkalmazás",
            'desc'  => "Vegye fel a Phoenix Whistleblowing mobilalkalmazást a csatornák kínálatába, amely elérhető mind Android, mind iOS platformokon. Mivel a smartphone-ok sok ember mindennapi életének szerves részét képezik, ennek az alkalmazásnak a kényelme lehetővé teszi az emberek számára, hogy bármikor, bárhol gyorsan jelentést tegyenek aggodalmaikról, elősegítve a nyitottság és a felelősség kultúráját.",
        ),
        // Irish
        'ga_IE' => array(
            'label' => "Aip Mhóibíleach",
            'desc'  => "Cuir an aip móibíleach Phoenix Whistleblowing le d'fhéileacán de chanaill, ar fáil ar an dó dhreas Android agus iOS. Agus é fonógaí bheith ina pháirt shuntasach den saol laethúil do mhórán daoine, tógann an aiseolas den aip seo cumhacht daoine go tapa cásanna a chur in iúl i gcónaí, áit ar bith, ag fás cultúir dáileachta agus freagrachta.",
        ),
        // Italian
        'it_IT' => array(
            'label' => "App mobile",
            'desc'  => "Aggiungi l'app mobile di segnalazione di Phoenix Whistleblowing al tuo insieme di canali, accessibile su entrambe le piattaforme Android e iOS. Con gli smartphone che sono una parte integrante della vita quotidiana per molti, la comodità di questa app consente alle persone di segnalare tempestivamente preoccupazioni in qualsiasi momento e ovunque, promuovendo una cultura di trasparenza e responsabilità.",
        ),
        // Latvian
        'lv_LV' => array(
            'label' => "Mobilā lietojumprogramma",
            'desc'  => "Pievienojiet Phoenix Whistleblowing mobilās lietojumprogrammas savam kanālu klāstam, pieejamo gan Android, gan iOS platformās. Ar viedierīcēm būdams svarīgs ikdienas dzīves elements daudziem, šīs lietojumprogrammas ērtums ļauj indivīdiem ātri ziņot par problēmām jebkurā laikā un jebkurā vietā, veicinot caurspīdības un atbildības kultūru.",
        ),
        // Lithuanian
        'lt_LT' => array(
            'label' => "Mobilioji programa",
            'desc'  => "Įtraukite „Phoenix Whistleblowing“ mobiliąją programėlę į savo kanalų sąrašą, prieinamą tiek „Android“, tiek „iOS“ platformose. Naudotojams, kuriems išmanieji telefonai yra svarbus kasdienio gyvenimo elementas, šios programėlės patogumas leidžia žmonėms greitai pranešti apie problemas bet kuriuo metu ir bet kur, skatinant skaidrumo ir atskaitomybės kultūrą.",
        ),
        // Maltese
        'mt_MT' => array(
            'label' => "App Mobili",
            'desc'  => "Żid l-app mobbli Phoenix Whistleblowing fil-mixja tal-kannali tiegħek, accessibbli fuq it-twieqi ta 'Android u iOS. Bil-telefoni intelli jiġu kkunsidrati parti essenzjali tal-ħajja ta 'kull ġurnata għaż-żewġin, il-komodità ta 'dawn l-appi jagħmel individwi ċari li jirrapportaw kuxjenzi b'freċċa, fejn jinsabu, jiffurmaw kultura ta 'trasparenza u responsabbiltà.",
        ),
        // Norwegian
        'nb_NO' => array(
            'label' => "Mobilapp",
            'desc'  => "Legg til den mobile Phoenix Whistleblowing-appen i ditt utvalg av kanaler, tilgjengelig på både Android- og iOS-plattformer. Ettersom smarttelefoner er en integrert del av dagliglivet for mange, gir denne appens bekvemmelighet enkeltpersoner muligheten til å raskt rapportere bekymringer når som helst og hvor som helst, og fremmer dermed en kultur for åpenhet og ansvarlighet.",
        ),
        // Polish
        'pl_PL' => array(
            'label' => "Aplikacja mobilna",
            'desc'  => "Dodaj mobilną aplikację Phoenix Whistleblowing do swojej gamy kanałów, dostępną na platformach Android i iOS. Zważywszy na to, że smartfony stanowią integralną część codziennego życia dla wielu osób, wygoda tej aplikacji umożliwia jednostkom szybkie zgłaszanie problemów w dowolnym miejscu i czasie, sprzyjając kulturze przejrzystości i odpowiedzialności.",
        ),
        // Portuguese
        'pt_PT' => array(
            'label' => "Aplicativo Móvel",
            'desc'  => "Adicione o aplicativo móvel Phoenix Whistleblowing à sua gama de canais, acessível em plataformas Android e iOS. Com os smartphones sendo parte integral da vida diária para muitos, a conveniência deste aplicativo capacita indivíduos a relatar rapidamente preocupações a qualquer momento e em qualquer lugar, promovendo uma cultura de transparência e responsabilidade.",
        ),
        // Romanian
        'ro_RO' => array(
            'label' => "Aplicație Mobilă",
            'desc'  => "Adăugați aplicația mobilă Phoenix Whistleblowing în gama dvs. de canale, accesibilă pe platformele Android și iOS. Având în vedere că smartphone-urile sunt parte integrantă a vieții zilnice pentru mulți oameni, confortul acestei aplicații îi împuternicește pe indivizi să raporteze rapid preocupările oriunde și oricând, promovând o cultură a transparenței și responsabilității.",
        ),
        // Slovak
        'sk_SK' => array(
            'label' => "Mobilná Aplikácia",
            'desc'  => "Pridajte mobilnú aplikáciu Phoenix Whistleblowing do vášho súboru kanálov, dostupných na platformách Android aj iOS. Keďže smartfóny sú pre mnohých neoddeliteľnou súčasťou každodenného života, pohodlie tejto aplikácie umožňuje jednotlivcom rýchlo nahlásiť obavy kedykoľvek a kdekoľvek, podporujúc kultúru transparentnosti a zodpovednosti.",
        ),
        // Slovenian
        'sl_SI' => array(
            'label' => "Mobilna aplikacija",
            'desc'  => "Dodajte mobilno aplikacijo Phoenix Whistleblowing v svojo paleto kanalov, dostopnih na platformah Android in iOS. Ker so pametni telefoni nepogrešljiv del vsakdanjega življenja za mnoge, vam ta aplikacija omogoča, da posamezniki hitro poročajo o težavah kadarkoli in kjerkoli, spodbujajoč kulturo transparentnosti in odgovornosti.",
        ),
        // Spanish
        'es_ES' => array(
            'label' => "Aplicación móvil",
            'desc'  => "Agrega la aplicación móvil de denuncia de Phoenix a tu conjunto de canales, accesible en las plataformas Android e iOS. Dado que los teléfonos inteligentes son una parte integral de la vida diaria para muchos, la conveniencia de esta aplicación permite a las personas reportar rápidamente preocupaciones en cualquier momento y lugar, fomentando así una cultura de transparencia y responsabilidad.",
        ),
        // Swedish
        'sv_SE' => array(
            'label' => "Mobilapp",
            'desc'  => "Lägg till Phoenix Whistleblowing-mobilappen i din uppsättning kanaler, tillgänglig på både Android- och iOS-plattformar. Eftersom smartphones är en integrerad del av dagliga livet för många, ger denna app's bekvämlighet individer möjlighet att snabbt rapportera bekymmer när som helst, var som helst, vilket främjar en kultur av öppenhet och ansvar.",
        ),
    ),
    /**
     * Email*
     */
    'email' => array(
        // English
        'en_US' => array( 
            'label' => "Email Inbox",
            'desc'  => "Enhance your whistleblowing channels by integrating a secure email address. When email messages are sent to the secure email address, our system automatically generates a disclosure upon receiving new emails, ensuring all essential information is included. Whistleblowers can conveniently attach supporting documents, evidence, or files to their emails, providing crucial context and evidence regarding reported misconduct. Add email addresses as needed for each pipeline (e.g., one per country).",
        ),
        // Basque
        'eu_ES' => array(
            'label' => "Posta Kutxa",
            'desc'  => "Zure zirriborrokan kanalak berritu seguruko posta helbide bat integratuz. Seguruko posta helbidera bidalitako mezu elektronikoak jasotzen direnean, gure sistema berriak zirriborro bat sortzen du mezu elektroniko berriak jasotzen direnean, informazio esanguratsua guztiak barne hartuz. Zirriborroak dokumentazio laguntzailea, frogak edo fitxategiak erantsi ditzakete bere mezu elektronikoei, aipaturiko malgutasunen inguruko testuinguru eta frogak emanez. Gehitu posta helbideak beharrezkoak direla bakoitzeko lineako (adibidez, herrialde bakoitzean bata).",
        ),
        // Bulgarian
        'bg_BG' => array(
            'label' => "Входящи имейли",
            'desc'  => "Подобрете каналите си за сигнализиране на нарушения, като включите защитен имейл адрес. Когато се изпращат имейл съобщения на защитения имейл адрес, нашата система автоматично генерира разкритие след получаването на нови имейли, като се гарантира, че всички съществени данни са включени. Сигнализаторите могат удобно да прикрепят подкрепящи документи, доказателства или файлове към своите имейли, като предоставят ключов контекст и доказателства относно докладваното престъпление. Добавете имейл адреси по нужда за всяка тръбопроводна система (например, по един за всяка държава).",
        ),
        // Croatian
        'hr' => array(
            'label' => "Poštanski sandučić",
            'desc'  => "Nadogradite kanale za prijavu nepravilnosti integriranjem sigurne adrese e-pošte. Kada se e-poštanske poruke pošalju na sigurnu adresu e-pošte, naš sustav automatski generira otkrivanje po primitku novih e-pošta, osiguravajući uključivanje svih bitnih informacija. Prijavitelji mogu jednostavno priložiti potporne dokumente, dokaze ili datoteke svojim e-poštnim porukama, pružajući ključni kontekst i dokaze o prijavljenom ponašanju. Dodajte adrese e-pošte po potrebi za svaki kanal (npr. po jedna po zemlji).",
        ),
        // Czech
        'cs_CZ' => array(
            'label' => "Doručená pošta",
            'desc'  => "Zlepšete své kanály pro oznamování nepravostí integrací zabezpečené e-mailové adresy. Když jsou e-mailové zprávy odeslány na zabezpečenou e-mailovou adresu, naši systém automaticky generuje zveřejnění při přijetí nových e-mailů a zajistí, že jsou zahrnuty veškeré podstatné informace. Whistlebloweři mohou pohodlně připojit podpůrné dokumenty, důkazy nebo soubory k jejich e-mailům, poskytujíce tak klíčový kontext a důkazy týkající se hlášených nepravostí. Přidejte e-mailové adresy podle potřeby pro každý kanál (např. jeden pro každou zemi).",
        ),
        // Danish
        'da_DK' => array(
            'label' => "E-mail indbakke",
            'desc'  => "Forbedre dine whistleblowing-kanaler ved at integrere en sikker e-mailadresse. Når e-mail-beskeder sendes til den sikre e-mail-adresse, genererer vores system automatisk en afsløring ved modtagelse af nye e-mails, hvilket sikrer, at alle væsentlige oplysninger er inkluderet. Whistleblowere kan bekvemt vedhæfte understøttende dokumenter, beviser eller filer til deres e-mails, hvilket giver afgørende kontekst og beviser vedrørende rapporteret uredelighed. Tilføj e-mailadresser efter behov for hver pipeline (f.eks. én pr. land).",
        ),
        // Dutch
        'nl_NL' => array(
            'label' => "E-mailinbox",
            'desc'  => "Versterk uw klokkenluiderskanalen door een beveiligd e-mailadres te integreren. Wanneer e-mailberichten naar het beveiligde e-mailadres worden gestuurd, genereert ons systeem automatisch een melding bij ontvangst van nieuwe e-mails, waarbij ervoor wordt gezorgd dat alle essentiële informatie is inbegrepen. Klokkenluiders kunnen handig ondersteunende documenten, bewijsmateriaal of bestanden aan hun e-mails toevoegen, wat cruciale context en bewijs levert met betrekking tot gemelde wangedrag. Voeg e-mailadressen toe zoals nodig voor elke pijplijn (bijv. één per land).",
        ),
        // Estonian
        'et' => array(
            'label' => "E-posti postkast",
            'desc'  => "Tugevdage oma teabelehtede kanaleid turvalise e-posti aadressi integreerimisega. Kui e-kirju saadetakse turvalisele e-posti aadressile, genereerib meie süsteem automaatselt avalduse uute e-kirjade saamisel, tagades, et kõik oluline teave on kaasatud. Whistleblowerid saavad mugavalt lisada oma e-kirjadele toetavad dokumendid, tõendid või failid, pakkudes olulist konteksti ja tõendeid teatatud ebaeetilise käitumise kohta. Lisage vajadusel iga torujuhtme jaoks e-posti aadressid (nt üks riigi kohta).",
        ),
        // Finnish
        'fi' => array(
            'label' => "Sähköpostilaatikko",
            'desc'  => "Paranna ilmiantokanaviasi integroimalla turvallinen sähköpostiosoite. Kun sähköpostiviestejä lähetetään turvalliseen sähköpostiosoitteeseen, järjestelmämme generoi automaattisesti ilmoituksen uusien sähköpostien vastaanottamisesta, varmistaen, että kaikki olennainen tieto sisältyy. Ilmiantajat voivat kätevästi liittää tukidokumentteja, todisteita tai tiedostoja sähköposteihinsa, tarjoten keskeisen kontekstin ja todistusaineiston ilmoitettuihin väärinkäytöksiin liittyen. Lisää tarvittaessa sähköpostiosoitteita jokaiseen kanavaan (esim. yksi per maa).",
        ),
        // French
        'fr_FR' => array(
            'label' => "Boîte de réception électronique",
            'desc'  => "Améliorez vos canaux de dénonciation en intégrant une adresse e-mail sécurisée. Lorsque des messages électroniques sont envoyés à l'adresse e-mail sécurisée, notre système génère automatiquement une divulgation dès réception des nouveaux e-mails, garantissant que toutes les informations essentielles sont incluses. Les dénonciateurs peuvent facilement joindre des documents à l'appui, des preuves ou des fichiers à leurs e-mails, fournissant un contexte crucial et des preuves concernant les actes répréhensibles signalés. Ajoutez des adresses e-mail au besoin pour chaque canal (par exemple, une par pays).",
        ),
        // German
        'de_DE' => array(
            'label' => "E-Mail-Posteingang",
            'desc'  => "Verbessern Sie Ihre Hinweisgeber-Kanäle, indem Sie eine sichere E-Mail-Adresse integrieren. Wenn E-Mail-Nachrichten an die sichere E-Mail-Adresse gesendet werden, generiert unser System automatisch eine Offenlegung beim Empfang neuer E-Mails und stellt sicher, dass alle wesentlichen Informationen enthalten sind. Hinweisgeber können bequem unterstützende Dokumente, Beweise oder Dateien an ihre E-Mails anhängen und so wichtige Kontexte und Beweise in Bezug auf gemeldetes Fehlverhalten liefern. Fügen Sie bei Bedarf E-Mail-Adressen für jede Pipeline hinzu (z. B. eine pro Land).",
        ),
        // Greek
        'el' => array(
            'label' => "Εισερχόμενα Email",
            'desc'  => "Βελτιώστε τα κανάλια σας για την αναφορά παραβιάσεων ενσωματώνοντας μια ασφαλή διεύθυνση email. Όταν αποστέλλονται email στην ασφαλή διεύθυνση email, το σύστημά μας δημιουργεί αυτόματα μια αποκάλυψη κατά την παραλαβή νέων email, εξασφαλίζοντας ότι όλες οι απαραίτητες πληροφορίες περιλαμβάνονται. Οι καταγγέλλοντες μπορούν εύκολα να επισυνάψουν υποστηρικτικά έγγραφα, αποδείξεις ή αρχεία στα email τους, παρέχοντας κρίσιμα στοιχεία και αποδείξεις σχετικά με τις αναφερόμενες παραβάσεις. Προσθέστε διευθύνσεις email όπως χρειάζεται για κάθε κανάλι (π.χ. μία ανά χώρα).",
        ),
        // Hungarian
        'hu_HU' => array(
            'label' => "E-mail bejövő levelek",
            'desc'  => "Javítsa a visszaélések bejelentésére szolgáló csatornáit egy biztonságos e-mail cím integrálásával. Amikor e-mail üzeneteket küldenek a biztonságos e-mail címre, rendszerünk automatikusan nyilvánosságra hozza az új e-mailek kézhezvételekor, biztosítva, hogy minden lényeges információt belefoglaljanak. A bejelentők kényelmesen csatolhatnak támogató dokumentumokat, bizonyítékokat vagy fájlokat az e-mailjeikhez, ezzel biztosítva a bejelentett visszaélésekkel kapcsolatos kulcsfontosságú kontextust és bizonyítékokat. Adjon hozzá e-mail címeket igény szerint minden csatornához (pl. egyet országonként).",
        ),
        // Irish
        'ga_IE' => array(
            'label' => "Bosca Iomphoist",
            'desc'  => "Cur le do chanaillí beartaithe trí threorach ríomhphost slán a chur leis. Nuair a sheoltar teachtaireachtaí ríomhphoist chuig an seoladh ríomhphoist slán, cuireann ár gcóras faoina luaimhe frithdhíolú nuair a fhaigheann sé ríomhphoist nua, ag a chinntiú go bhfuil gach eolas riachtanach san áireamh. Is féidir le glaotóirí doiciméid tacúla, fianaise nó comhad a ghabháil go héasca do na ríomhphoist, ag cur in iúl comhthéacsúlacht agus fianaise riachtanacha maidir le míchiallachtaí tuairimí. Cuir seoladh ríomhphoist le gach píopaí ag teastáil (m.sh., ceann in aghaidh gach tíre).",
        ),
        // Italian
        'it_IT' => array(
            'label' => "Casella di posta elettronica",
            'desc'  => "Migliora i tuoi canali di whistleblowing integrando un indirizzo e-mail sicuro. Quando i messaggi di posta elettronica vengono inviati all'indirizzo e-mail sicuro, il nostro sistema genera automaticamente una segnalazione al ricevimento di nuove e-mail, assicurando che tutte le informazioni essenziali siano incluse. I whistleblower possono comodamente allegare documenti di supporto, prove o file alle loro e-mail, fornendo contesto e prove cruciali riguardo al comportamento scorretto segnalato. Aggiungi indirizzi e-mail secondo necessità per ciascuna pipeline (ad esempio, uno per paese).",
        ),
        // Latvian
        'lv_LV' => array(
            'label' => "E-pasta iesūtne",
            'desc'  => "Uzlabojiet savus trauksmes celšanas kanālus, integrējot drošu e-pasta adresi. Kad e-pasta ziņojumi tiek sūtīti uz drošo e-pasta adresi, mūsu sistēma automātiski ģenerē atklāšanu, saņemot jaunus e-pastus, nodrošinot, ka ir iekļauta visa būtiskā informācija. Trauksmes cēlāji var ērti pievienot atbalsta dokumentus, pierādījumus vai failus saviem e-pastiem, sniedzot būtisku kontekstu un pierādījumus par ziņoto pārkāpumu. Pievienojiet e-pasta adreses pēc vajadzības katrai cauruļvadu sistēmai (piemēram, pa vienai katrai valstij).",
        ),
        // Lithuanian
        'lt_LT' => array(
            'label' => "El. pašto dėžutė",
            'desc'  => "Pagerinkite savo pranešimų apie netinkamus veiksmus kanalus, integruodami saugų el. pašto adresą. Kai el. pašto pranešimai siunčiami saugiu el. pašto adresu, mūsų sistema automatiškai sukuria atskleidimą gavus naujus el. laiškus, užtikrinant, kad būtų įtraukta visa svarbi informacija. Pranešėjai gali patogiai pridėti palaikomuosius dokumentus, įrodymus ar failus prie savo el. laiškų, pateikdami svarbų kontekstą ir įrodymus apie praneštą netinkamą elgesį. Pridėkite el. pašto adresus pagal poreikį kiekvienam vamzdynui (pvz., po vieną kiekvienai šaliai).",
        ),
        // Maltese
        'mt_MT' => array(
            'label' => "Inkassar Email",
            'desc'  => "Żid il-kannali tiegħek dwar il-Whistleblowing billi tħaddem indirizz ta 'email sikur. Meta jissejħu messaġġi ta 'email fl-indirizz ta' email sikur, is-sistema tagħna tigħin awtomatikament jedd li jinkludu d-diskussjoni meta jingħataj messaġġi ġodda, biex jintlaħqu l-informazzjoni essenzjali kollha. Il-whistleblowers jistgħu jittaggħu docunenti ta 'appoġġ, evidenza, jew fajls għall-messaġġi tagħhom, biex jipprovdu kontest u evidenza kruċjali dwar il-misħuta riċentament rapportata. Żid indirizzi email skont il-bżonn għal kull pipilajn (eż., wieħed għal kull pajjiż).",
        ),
        // Norwegian Nynorsk
        'nb_NO' => array(
            'label' => "E-postinnboks",
            'desc'  => "Forbedre dine varslingskanaler ved å integrere en sikker e-postadresse. Når e-postmeldinger sendes til den sikre e-postadressen, genererer vårt system automatisk en offentliggjøring ved mottak av nye e-postmeldinger, og sikrer at all nødvendig informasjon er inkludert. Varslere kan enkelt vedlegge støttedokumenter, bevis eller filer til e-postene sine, og gir dermed viktig sammenheng og bevis angående rapportert mislighold. Legg til e-postadresser etter behov for hver rørledning (f.eks. en per land).",
        ),
        // Polish
        'pl_PL' => array(
            'label' => "Skrzynka odbiorcza e-mail",
            'desc'  => "Popraw swoje kanały zgłaszania nieprawidłowości poprzez integrację bezpiecznego adresu e-mail. Gdy wiadomości e-mail są wysyłane na bezpieczny adres e-mail, nasz system automatycznie generuje ujawnienie po otrzymaniu nowych e-maili, zapewniając, że wszystkie istotne informacje są zawarte. Sygnaliści mogą wygodnie dołączać dokumenty wspierające, dowody lub pliki do swoich e-maili, dostarczając kluczowy kontekst i dowody dotyczące zgłaszanego wykroczenia. Dodaj adresy e-mail w razie potrzeby dla każdej rury (np. jeden na kraj).",
        ),
        // Portuguese
        'pt_PT' => array(
            'label' => "Caixa de entrada de e-mail",
            'desc'  => "Melhore seus canais de denúncia integrando um endereço de e-mail seguro. Quando mensagens de e-mail são enviadas para o endereço de e-mail seguro, nosso sistema gera automaticamente uma divulgação ao receber novos e-mails, garantindo que todas as informações essenciais sejam incluídas. Denunciantes podem anexar convenientemente documentos de suporte, evidências ou arquivos aos seus e-mails, fornecendo contexto e evidências cruciais sobre as irregularidades relatadas. Adicione endereços de e-mail conforme necessário para cada pipeline (por exemplo, um por país).",
        ),
        // Romanian
        'ro_RO' => array(
            'label' => "Căsuță de e-mail",
            'desc'  => "Îmbunătățiți canalele de avertizare prin integrarea unei adrese de e-mail securizate. Atunci când mesajele de e-mail sunt trimise la adresa de e-mail securizată, sistemul nostru generează automat o dezvăluire la primirea noilor e-mailuri, asigurându-se că toate informațiile esențiale sunt incluse. Avertizorii pot atașa convenabil documente de susținere, dovezi sau fișiere la e-mailurile lor, oferind un context și dovezi esențiale cu privire la neregulile raportate. Adăugați adrese de e-mail după cum este necesar pentru fiecare canal (de exemplu, una pentru fiecare țară).",
        ),
        // Slovak
        'sk_SK' => array(
            'label' => "Prijatie e-mailov",
            'desc'  => "Vylepšite svoje kanály na oznamovanie nesprávneho konania integráciou zabezpečenej e-mailovej adresy. Keď sa e-mailové správy odosielajú na zabezpečenú e-mailovú adresu, náš systém automaticky vygeneruje oznámenie po prijatí nových e-mailov, čím sa zabezpečí, že všetky podstatné informácie sú zahrnuté. Oznámenia môžu pohodlne priložiť podporné dokumenty, dôkazy alebo súbory k svojim e-mailom, čím poskytujú kľúčový kontext a dôkazy týkajúce sa hláseného nesprávneho konania. Pridajte e-mailové adresy podľa potreby pre každý kanál (napr. jednu pre každú krajinu).",
        ),
        // Slovenian
        'sl_SI' => array(
            'label' => "E-poštni predal",
            'desc'  => "Izboljšajte svoje kanale za prijavo nepravilnosti z integracijo varnega e-poštnega naslova. Ko se e-poštna sporočila pošljejo na varni e-poštni naslov, naš sistem samodejno ustvari razkritje ob prejemu novih e-poštnih sporočil, pri čemer zagotavlja, da so vključene vse bistvene informacije. Prijavitelji nepravilnosti lahko priročno priložijo podporne dokumente, dokaze ali datoteke svojim e-poštnim sporočilom, s čimer zagotovijo ključni kontekst in dokaze o prijavljenem neprimernem ravnanju. Dodajte e-poštne naslove po potrebi za vsak kanal (npr. enega na državo).",
        ),
        // Spanish
        'es_ES' => array(
            'label' => "Bandeja de entrada de correo electrónico",
            'desc'  => "Mejore sus canales de denuncia de irregularidades integrando una dirección de correo electrónico segura. Cuando se envían mensajes de correo electrónico a la dirección de correo electrónico segura, nuestro sistema genera automáticamente una divulgación al recibir nuevos correos electrónicos, asegurando que se incluya toda la información esencial. Los denunciantes pueden adjuntar convenientemente documentos de respaldo, pruebas o archivos a sus correos electrónicos, proporcionando un contexto y evidencia cruciales sobre la conducta denunciada. Agregue direcciones de correo electrónico según sea necesario para cada canal (por ejemplo, una por país).",
        ),
        // Swedish
        'sv_SE' => array(
            'label' => "E-postinkorg",
            'desc'  => "Förbättra dina visselblåsningskanaler genom att integrera en säker e-postadress. När e-postmeddelanden skickas till den säkra e-postadressen genererar vårt system automatiskt ett avslöjande vid mottagandet av nya e-postmeddelanden, vilket säkerställer att all väsentlig information inkluderas. Visselblåsare kan enkelt bifoga stöddokument, bevis eller filer till sina e-postmeddelanden, vilket ger viktig kontext och bevis om den rapporterade felaktigheten. Lägg till e-postadresser efter behov för varje kanal (t.ex. en per land).",
        ),
    ),
    /**
     * Phone*
     */
    'phone' => array(
        // English
        'en_US' => array( 
            'label' => "Phone Numbers (Display)",
            'desc'  => "List phone numbers among the available channels for reporting disclosures. This offers an immediate and direct means of communication, allowing whistleblowers to report critical information in real-time to your operator. This can be especially important when time-sensitive matters need to be addressed promptly or when interaction is required. For each phone number listed, clients will be charged for the display space. <b>Phoenix Whistleblowing Software does not serve as a telephone provider and does not provide phone numbers.</b>",
        ),
        // Euskara
        'eu_ES' => array( 
            'label' => "Telefono zenbakiak (Bistaratu)",
            'desc'  => "Telefono zenbakiak eskuragarri dauden kanalen artean zerrendatu. Hau komunikazio modu zuzena eta zuzena ematen du, zirriborroak eragiten diren informazio kritikoa zuzenean operadoreari iragartzeko. Hau bereziki garrantzitsua izan daiteke gauza denborazkoak azkar egiten saiatu behar direnean edo interakzioa behar denbora. Zerrendatutako telefono zenbakietarako, bezeroak bistaratze espazioa ordaindu beharko diete. Phoenix Whistleblowing Software-ak telefono hornitzaile gisa ez du jarduten eta telefono zenbakiak ez ditu eskaintzen.",
        ),
        // Bulgarian
        'bg_BG' => array( 
            'label' => "Телефонни номера (Показване)",
            'desc'  => "Изброявайте телефонни номера сред наличните канали за докладване на разкрития. Това предлага незабавен и директен начин за комуникация, като позволява на сигнализаторите да докладват критична информация в реално време на ваш оператор. Това може да бъде особено важно, когато е необходимо бързо да се разгледат въпроси, свързани със време, или когато е необходимо взаимодействие. За всеки изброен телефонен номер клиентите ще бъдат таксувани за мястото за показване. Phoenix Whistleblowing Software не служи като доставчик на телефонни услуги и не предоставя телефонни номера.",
        ),
        // Croatian
        'hr' => array( 
            'label' => "Telefonski brojevi (Prikaz)",
            'desc'  => "Nabrojite telefonske brojeve među dostupnim kanalima za prijavu otkrića. To nudi neposredan i direktan način komunikacije, omogućavajući prijaviteljima da u stvarnom vremenu izvijeste kritične informacije svom operatoru. To može biti posebno važno kada je potrebno brzo riješiti pitanja koja su osjetljiva na vrijeme ili kada je potrebna interakcija. Za svaki navedeni telefonski broj klijenti će biti naplaćeni za prikazani prostor. Phoenix Whistleblowing Software ne djeluje kao pružatelj telefonskih usluga i ne pruža telefonske brojeve.",
        ),
        // Czech
        'cs_CZ' => array( 
            'label' => "Telefonní čísla (Zobrazení)",
            'desc'  => "Vypište telefonní čísla mezi dostupnými kanály pro hlášení zveřejnění. To nabízí okamžitý a přímý způsob komunikace, který umožňuje whistleblowerům hlásit kritické informace v reálném čase vašemu operátorovi. To může být zvláště důležité, když je třeba rychle řešit záležitosti citlivé na čas, nebo když je vyžadována interakce. Za každé uvedené telefonní číslo budou klienti účtováni za zobrazovací prostor. Software Phoenix Whistleblowing neslouží jako poskytovatel telefonních služeb a neposkytuje telefonní čísla.",
        ),
        // Danish
        'da_DK' => array( 
            'label' => "Telefonnumre (skærm)",
            'desc'  => "Angiv telefonnumre blandt de tilgængelige kanaler til rapportering af afsløringer. Dette giver et øjeblikkeligt og direkte kommunikationsmiddel, der giver whistleblowere mulighed for at rapportere kritisk information i realtid til din operatør. Dette kan især være vigtigt, når tidsfølsomme sager skal behandles hurtigt, eller når interaktion er påkrævet. For hvert anført telefonnummer vil klienter blive opkrævet for visningspladsen. Phoenix Whistleblowing Software fungerer ikke som telefonudbyder og oplyser ikke telefonnumre.",
        ),
        // Dutch
        'nl_NL' => array( 
            'label' => "Telefoonnummers (Weergave)",
            'desc'  => "Vermeld telefoonnummers tussen de beschikbare kanalen voor het melden van meldingen. Dit biedt een directe en directe communicatiemogelijkheid, waardoor klokkenluiders kritieke informatie in realtime aan uw operator kunnen melden. Dit kan vooral belangrijk zijn wanneer tijdsgevoelige zaken snel moeten worden aangepakt of wanneer interactie vereist is. Voor elk vermeld telefoonnummer worden kosten in rekening gebracht voor de weergaveruimte. Phoenix Klokkenluidersoftware fungeert niet als telefoonprovider en verstrekt geen telefoonnummers.",
        ),
        // Estonian
        'et' => array( 
            'label' => "Telefoninumbrid (Kuva)",
            'desc'  => "Loetlege telefoninumbrid kättesaadavate kanalite hulgas avalduste esitamiseks. See pakub viivitamatut ja otsest suhtlusvahendit, mis võimaldab whistlebloweritel raporteerida kriitilist teavet reaalajas teie operaatorile. See võib olla eriti oluline, kui ajatundlikud küsimused tuleb lahendada kiiresti või kui on vajalik suhtlus. Iga loetletud telefoni numbri eest arvestatakse klientidele kuvamisruumi eest tasu. Phoenixi Whistleblowing tarkvara ei tegutse telefoniteenuse pakkujana ega paku telefoninumbreid.",
        ),
        // Finnish
        'fi' => array( 
            'label' => "Puhelinnumerot (Näyttö)",
            'desc'  => "Listaa puhelinnumerot saatavilla olevien kanavien joukkoon ilmiantojen raportoimiseksi. Tämä tarjoaa välittömän ja suoran viestintäväylän, mahdollistaen ilmiantajien raportoida kriittisiä tietoja reaaliajassa toimijoillesi. Tämä voi olla erityisen tärkeää, kun kiireellisiä asioita on käsiteltävä nopeasti tai kun vuorovaikutusta tarvitaan. Jokaisesta listatusta puhelinnumerosta veloitetaan asiakkailta näyttötilan käytöstä. Phoenixin ilmiantosovellus ei toimi puhelinpalveluntarjoajana eikä tarjoa puhelinnumeroita.",
        ),
        // French
        'fr_FR' => array( 
            'label' => "Numéros de téléphone (Affichage)",
            'desc'  => "Listez les numéros de téléphone parmi les canaux disponibles pour le signalement des divulgations. Cela offre un moyen de communication immédiat et direct, permettant aux lanceurs d'alerte de rapporter des informations critiques en temps réel à votre opérateur. Cela peut être particulièrement important lorsque des questions sensibles au temps doivent être traitées rapidement ou lorsque une interaction est nécessaire. Pour chaque numéro de téléphone répertorié, les clients seront facturés pour l'espace d'affichage. Le logiciel Phoenix Whistleblowing ne sert pas de fournisseur de services téléphoniques et ne fournit pas de numéros de téléphone.",
        ),
        // German
        'de_DE' => array( 
            'label' => "Telefonnummern (Anzeige)",
            'desc'  => "Listen Sie Telefonnummern unter den verfügbaren Kanälen zur Meldung von Enthüllungen auf. Dies bietet eine sofortige und direkte Kommunikationsmöglichkeit, die es Whistleblowern ermöglicht, kritische Informationen in Echtzeit an Ihren Betreiber zu melden. Dies kann besonders wichtig sein, wenn zeitkritische Angelegenheiten schnell bearbeitet werden müssen oder wenn eine Interaktion erforderlich ist. Für jede aufgeführte Telefonnummer werden Kunden für den Anzeigebereich berechnet. Die Phoenix Whistleblowing-Software fungiert nicht als Telefonanbieter und stellt keine Telefonnummern zur Verfügung.",
        ),
        // Greek
        'el' => array( 
            'label' => "Τηλεφωνικοί Αριθμοί (Εμφάνιση)",
            'desc'  => "Καταγράψτε τους τηλεφωνικούς αριθμούς μεταξύ των διαθέσιμων καναλιών για την αναφορά αποκαλύψεων. Αυτό προσφέρει άμεσο και άμεσο τρόπο επικοινωνίας, επιτρέποντας στους καταγγέλτες να αναφέρουν κρίσιμες πληροφορίες πραγματικού χρόνου στον τελεστή σας. Αυτό μπορεί να είναι ιδιαίτερα σημαντικό όταν πρέπει να διεκπεραιώνονται ζητήματα που είναι ευαίσθητα στο χρόνο ή όταν απαιτείται αλληλεπίδραση. Για κάθε καταχωρημένο τηλεφωνικό αριθμό, οι πελάτες θα χρεώνονται για τον χώρο εμφάνισης. Το λογισμικό Phoenix Whistleblowing δεν λειτουργεί ως πάροχος τηλεφωνικών υπηρεσιών και δεν παρέχει τηλεφωνικούς αριθμούς.",
        ),
        // Hungarian
        'hu_HU' => array( 
            'label' => "Telefonszámok (Megjelenítés)",
            'desc'  => "Sorolja fel a telefonszámokat az elérhető csatornák között a felfedések bejelentésére. Ez azonnali és közvetlen kommunikációs lehetőséget kínál, amely lehetővé teszi a klokkenluiders számára, hogy valós időben kritikus információkat jelentsenek be az üzemeltetőjüknek. Ez különösen fontos lehet, ha időszerű ügyeket kell gyorsan kezelni vagy ha szükség van az interakcióra. Minden felsorolt ​​telefonszám után ügyfeleknek díjat számítanak fel a megjelenítési térért. A Phoenix Whistleblowing szoftver nem működik telefon szolgáltatóként és nem biztosít telefonszámokat.",
        ),
        // Irish
        'ga_IE' => array(
            'label' => "Uimhreacha Fóna (Taispeáint)",
            'desc'  => "Liostaigh uimhreacha teileafóin i measc na gcairteailí atá ar fáil chun brath a dhéanamh. Is bealach cumarsáide díreach agus láithreach é seo, ag ligean do dhéagóirí faisnéis thábhachtach a thabhairt don oibreoir i gceart-am. Is féidir go mbeadh sé seo go háirithe tábhachtach nuair atá nithe a bhaineann leis an am ag teastáil chun déileáil go gasta nó nuair is gá éagsúlacht. Ar gach uimhir theileafóin atá liostaithe, bainfidh custaiméirí táille as an spás taispeántais. Ní sholáthraíonn Bogearraí Fiosruithe Phoenix mar sholáthraí teileafóin agus ní sholáthraíonn sé uimhreacha teileafóin.",
        ),
        // Italian
        'it_IT' => array( 
            'label' => "Numeri di telefono (Visualizzazione)",
            'desc'  => "Elenca i numeri di telefono tra i canali disponibili per la segnalazione delle rivelazioni. Questo offre un mezzo di comunicazione immediato e diretto, consentendo ai whistleblower di segnalare informazioni critiche in tempo reale al vostro operatore. Questo può essere particolarmente importante quando è necessario affrontare rapidamente questioni sensibili al tempo o quando è richiesta interazione. Per ciascun numero di telefono elencato, ai clienti verrà addebitato lo spazio di visualizzazione. Il software Phoenix Whistleblowing non funge da fornitore telefonico e non fornisce numeri di telefono.",
        ),
        // Latvian
        'lv_LV' => array( 
            'label' => "Telefona numuri (Parādīt)",
            'desc'  => "Uzskaitiet telefona numurus starp pieejamajiem kanāliem paziņojumiem par atklājumiem. Tas piedāvā nekavējošu un tiešu saziņas līdzekli, ļaujot ziņotājiem reālajā laikā paziņot kritisku informāciju jūsu operatoram. Tas var būt īpaši svarīgi, ja jārisina laika jutīgas lietas ātri vai ja nepieciešama mijiedarbība. Par katru uzskaitīto telefona numuru klientiem tiks rēķināts par parādīšanas vietu. Phoenix Whistleblowing programmatūra nedarbojas kā telefona pakalpojumu sniedzējs un nepiedāvā telefona numurus.",
        ),
        // Lithuanian
        'lt_LT' => array( 
            'label' => "Telefonų numeriai (Rodyti)",
            'desc'  => "Išvardykite telefonų numerius tarp prieinamų kanalų pranešimams apie atskleidimus. Tai suteikia nedelsiant ir tiesioginį bendravimo būdą, leidžiantį kaltintojams realiuoju laiku pranešti kritinę informaciją jūsų operatoriui. Tai gali būti ypatingai svarbu, kai skubiai reikia spręsti laiko jautrius klausimus arba kai reikalingas sąveikos. Už kiekvieną išvardytą telefonų numerį klientai bus apmokestinti už rodymo vietą. „Phoenix Whistleblowing“ programa veikia ne kaip telefonų paslaugų teikėjas ir nesuteikia telefono numerių.",
        ),
        // Maltese
        'mt_MT' => array( 
            'label' => "Numri tat-telefown (Uriżża)",
            'desc'  => "Elenca n-numri tat-telefown bejn il-kanali disponibbli għar-rapportar tad-diskussjonijiet. Dan joffri mezz ta’ komunikazzjoni immedjata u dirett, li permezz tiegħu l-ħaddiema għan-niddisċisti jistgħu jirrapportaw informazzjoni kritika f’real-time lill-operatur tiegħek. Dan jista’ jkun importanti speċjalment meta affarijiet sensitivi għall-ħin jeħtieġu jitqiesu bil-kbir jew meta hemm bżonn ta’ interazzjoni. Għal kull numru tat-telefown li jiġi elenkjat, il-klijenti se jinħargu għal is-spażju tad-displaj. Il-Software tal-Whistleblowing tal-Phoenix ma jgħinx bħala provvitur tal-telefown u ma jipprovdix numri tat-telefown.",
        ),
        // Norwegian Nynorsk
        'nb_NO' => array( 
            'label' => "Telefonnummer (Visning)",
            'desc'  => "List telefonnummer blant de tilgjengelege kanalane for å rapportere avsløringar. Dette gir ei øyeblikkeleg og direkte kommunikasjonsmåte, og gjer det mogleg for varslarar å rapportere kritisk informasjon i sanntid til operatøren din. Dette kan vere spesielt viktig når tidssensitive saker må handsamast raskt, eller når interaksjon er nødvendig. For kvart oppført telefonnummer vil kundane bli belasta for visingsplassen. Phoenix Whistleblowing Software fungerer ikkje som telefonleverandør og gir ikkje telefonnummer.",
        ),
        // Polish
        'pl_PL' => array( 
            'label' => "Numery telefonów (Wyświetlanie)",
            'desc'  => "Wymień numery telefonów wśród dostępnych kanałów do zgłaszania nieprawidłowości. Zapewnia to natychmiastową i bezpośrednią formę komunikacji, pozwalając pracownikom na przekazywanie istotnych informacji w czasie rzeczywistym do twojego operatora. Może to być szczególnie istotne, gdy kwestie związane z czasem wymagają szybkiego działania lub gdy potrzebna jest interakcja. Za wyświetlaną przestrzeń klientom zostaną naliczone opłaty za każdy wymieniony numer telefonu. Oprogramowanie Phoenix Whistleblowing nie działa jako dostawca usług telefonicznych i nie dostarcza numerów telefonów.",
        ),
        // Portuguese
        'pt_PT' => array( 
            'label' => "Números de telefone (Exibição)",
            'desc'  => "Liste os números de telefone entre os canais disponíveis para relatar divulgações. Isso oferece um meio de comunicação imediato e direto, permitindo que denunciantes relatem informações críticas em tempo real ao seu operador. Isso pode ser especialmente importante quando questões urgentes precisam ser tratadas prontamente ou quando a interação é necessária. Para cada número de telefone listado, os clientes serão cobrados pelo espaço de exibição. O Software de Denúncias Phoenix não atua como provedor de serviços telefônicos e não fornece números de telefone.",
        ),
        // Romanian
        'ro_RO' => array( 
            'label' => "Numere de telefon (Afișare)",
            'desc'  => "Listați numerele de telefon între canalele disponibile pentru raportarea dezvăluirilor. Aceasta oferă un mijloc de comunicare imediat și direct, permițând denunțătorilor să raporteze informații critice în timp real operatorului dumneavoastră. Acest lucru poate fi deosebit de important atunci când este necesar să abordați rapid chestiuni sensibile la timp sau când este necesară interacțiunea. Pentru fiecare număr de telefon listat, clienții vor fi taxați pentru spațiul de afișare. Software-ul Phoenix Whistleblowing nu servește ca furnizor de telefonie și nu furnizează numere de telefon.",
        ),
        // Slovak
        'sk_SK' => array( 
            'label' => "Telefónne čísla (Zobrazenie)",
            'desc'  => "Zoznam telefónnych čísel medzi dostupnými kanálmi na oznamovanie verejnosti. To ponúka okamžitý a priamy spôsob komunikácie, ktorý umožňuje oznamovateľom nahlásiť kritické informácie v reálnom čase vašim operátorom. To môže byť obzvlášť dôležité, keď je potrebné promptne riešiť časovo citlivé záležitosti alebo keď je vyžadovaná interakcia. Za každé uvedené telefónne číslo budú klienti účtovaní za zobrazovací priestor. Softvér Phoenix Whistleblowing neslúži ako poskytovateľ telefónnych služieb a neposkytuje telefónne čísla.",
        ),
        // Slovenian
        'sl_SI' => array( 
            'label' => "Telefonske številke (Prikaz)",
            'desc'  => "Naštetie telefonskih številk med razpoložljivimi kanali za prijavo razkritij. To ponuja takojšen in neposreden način komuniciranja, ki omogoča prijaviteljem, da v realnem času poročajo kritične informacije vašemu operaterju. To je lahko še posebej pomembno, kadar je treba časovno občutljive zadeve takoj obravnavati ali kadar je potrebna interakcija. Za vsako navedeno telefonsko številko bodo stranke zaračunane za prikazni prostor. Programska oprema Phoenix Whistleblowing ne deluje kot ponudnik telefonskih storitev in ne zagotavlja telefonskih številk.",
        ),
        // Spanish
        'es_ES' => array( 
            'label' => "Números de teléfono (Visualización)",
            'desc'  => "Enumera números de teléfono entre los canales disponibles para reportar divulgaciones. Esto ofrece un medio de comunicación inmediato y directo, permitiendo a los denunciantes reportar información crítica en tiempo real a tu operador. Esto puede ser especialmente importante cuando se necesitan abordar asuntos urgentes o cuando se requiere interacción. Por cada número de teléfono listado, se cobrará a los clientes por el espacio de visualización. El Software de Denuncia de Phoenix no sirve como proveedor de telefonía y no proporciona números de teléfono.",
        ),
        // Swedish
        'sv_SE' => array( 
            'label' => "Telefonnummer (Visning)",
            'desc'  => "Lista telefonnummer bland de tillgängliga kanalerna för rapportering av avslöjanden. Detta erbjuder en omedelbar och direkt kommunikationsväg, vilket gör att whistleblowers kan rapportera kritisk information i realtid till din operatör. Detta kan vara särskilt viktigt när tidskänsliga frågor måste hanteras snabbt eller när interaktion krävs. För varje listat telefonnummer debiteras kunder för visningsutrymmet. Phoenix Whistleblowing Software fungerar inte som telefonleverantör och tillhandahåller inte telefonnummer.",
         ),
    ),
    /**
     * Instant Messaging - im*
     */
    'im' => array(
        // English
        'en_US' => array(
            'label' => "Instant Messaging (Display)",
            'desc'  => "Display your instant messaging accounts from platforms like WhatsApp, Skype, or Telegram on your dedicated website. Instant messaging apps are widely used and familiar to many people, making it easy for them to reach out. Based on our experience, these apps are among the most popular channels used by whistleblowers. This accessibility encourages more individuals to come forward with their concerns. <b>Phoenix Whistleblowing Software is not affiliated with any instant messaging service and does not integrate with your instant messaging account.</b>"
        ),
        // Euskara
        'eu_ES' => array(
            'label' => "Mezu instanteko (Bistaratu)",
            'desc'  => "Erakutsi zure WhatsApp, Skype edo Telegram bezalako plataformetako mezu instanteko kontuak zure webgune espezializatuan. Mezu instanteko aplikazioak oso erabilia eta ezaguna dira, horrek erraz egiten die haien lotura izatea. Gure esperientzia oinarrituta, aplikazio horiek denuntziatzaileek erabiltzen dituzten kanalu popularrak dira. Hauen eskuragarritasunak pertsona gehiago bideratzea bultzatzen du haien arazoei aurre egiteko. <b>Phoenix Whistleblowing Softwarek ez du inolako mezu instanteko zerbitzuekin harremanik ez eta ez du zure mezu instanteko kontuarekin integra.</b>"
        ),
        // Bulgarian
        'bg_BG' => array(
            'label' => "Моментално съобщение (Показване)",
            'desc'  => "Покажете вашите акаунти за моментално съобщение от платформи като WhatsApp, Skype или Telegram на вашата специализирана уеб страница. Приложенията за моментално съобщение са широко използвани и познати на много хора, което им улеснява връзката. На базата на нашето опит, тези приложения са сред най-популярните канали, използвани от информатори. Тази достъпност стимулира повече хора да излязат напред със своите притеснения. <b>Phoenix Whistleblowing Software не е свързан с нито един сервиз за моментално съобщение и не се интегрира с вашия акаунт за моментално съобщение.</b>"
        ),
        // Croatian
        'hr' => array(
            'label' => "Trenutne poruke (Prikaz)",
            'desc'  => "Prikažite svoje račune za trenutne poruke s platformi poput WhatsAppa, Skypea ili Telegrama na svojoj posvećenoj web stranici. Aplikacije za trenutne poruke široko su korištene i poznate mnogima, što im olakšava kontakt. Na temelju našeg iskustva, ove aplikacije su među najpopularnijim kanalima koje koriste prijavljivači. Ova dostupnost potiče više pojedinaca da iznesu svoje zabrinutosti. <b>Phoenix Whistleblowing Software nije povezan s bilo kojom uslugom trenutnih poruka i ne integrira se s vašim računom za trenutne poruke.</b>"
        ),
        // Czech
        'cs_CZ' => array(
            'label' => "Okamžitá komunikace (Zobrazení)",
            'desc'  => "Zobrazte své účty pro okamžitou komunikaci z platform jako WhatsApp, Skype nebo Telegram na vaší dedicované webové stránce. Aplikace pro okamžitou komunikaci jsou široce používány a známé mnoha lidem, což jim usnadňuje kontaktování vás. Na základě našeho zkušeností jsou tyto aplikace mezi nejoblíbenějšími kanály používanými whistlebloweri. Tato dostupnost podněcuje více jednotlivců, aby přišli s jejich obavami. <b>Phoenix Whistleblowing Software není spojen s žádnou službou pro okamžitou komunikaci a nepropojuje se s vaším účtem pro okamžitou komunikaci.</b>"
        ),
        // Danish
        'da_DK' => array(
            'label' => "Instant Messaging (skærm)",
            'desc'  => "Vis dine instant messaging-konti fra platforme som WhatsApp, Skype eller Telegram på dit dedikerede websted. Instant messaging-apps er meget udbredte og velkendte for mange mennesker, hvilket gør det nemt for dem at nå ud. Baseret på vores erfaring er disse apps blandt de mest populære kanaler, der bruges af whistleblowere. Denne tilgængelighed moedgger flere individer til at komme frem med deres bekymringer. <b>Phoenix Whistleblowing Software er ikke tilknyttet nogen instant messaging-tjeneste og kan ikke integreres med din instant messaging-konto.</b>"
        ),
        // Dutch
        'nl_NL' => array(
            'label' => "Instant Messaging (Weergave)",
            'desc'  => "Geef uw instant messaging-accounts van platforms zoals WhatsApp, Skype of Telegram weer op uw toegewijde website. Instant messaging-apps worden veel gebruikt en zijn bekend bij veel mensen, waardoor het gemakkelijk voor hen is om contact op te nemen. Op basis van onze ervaring behoren deze apps tot de meest populaire kanalen die door klokkenluiders worden gebruikt. Deze toegankelijkheid moedigt meer individuen aan om met hun zorgen naar voren te komen. <b>Phoenix Klokkenluidersoftware is niet verbonden met enige instant messaging-service en integreert niet met uw instant messaging-account.</b>"
        ),
        // Estonian
        'et' => array(
            'label' => "Kiirsõnum (Kuva)",
            'desc'  => "Kuvage oma kiirsõnumite kontod platvormidel nagu WhatsApp, Skype või Telegram teie pühendatud veebisaidil. Kiirsõnumirakendusi kasutatakse laialdaselt ja need on paljudele inimestele tuttavad, mis võimaldab neil hõlpsasti teiega ühendust võtta. Meie kogemuste põhjal on need rakendused ühed enimkasutatud kanalid whistleblowersite poolt. See kättesaadavus julgustab rohkem inimesi esitama oma muresid. <b>Phoenixi Whistleblowing tarkvara ei ole seotud ühegi kiirsõnumiteenusega ega integreeru teie kiirsõnumite kontoga.</b>"
        ),
        // Finnish
        'fi' => array(
            'label' => "Pikaviestintä (Näyttö)",
            'desc'  => "Näytä pikaviestintätilisi alustoilla kuten WhatsApp, Skype tai Telegram omalla omistetulla verkkosivustollasi. Pikaviestisovellukset ovat laajalti käytettyjä ja tuttuja monille, mikä tekee heidän tavoittamisesta helppoa. Kokemuksemme perusteella nämä sovellukset ovat yksiä klokeissa käytetyimmistä kanavista. Tämä saavutettavuus rohkaisee useampia yksilöitä astumaan esiin huolillaan. <b>Phoenixin ilmiantosovellus ei ole liittoutunut minkään pikaviestipalvelun kanssa eikä integroidu pikaviestitiliisi.</b>"
        ),
        // French
        'fr_FR' => array(
            'label' => "Messagerie instantanée (Affichage)",
            'desc'  => "Affichez vos comptes de messagerie instantanée provenant de plateformes comme WhatsApp, Skype ou Telegram sur votre site web dédié. Les applications de messagerie instantanée sont largement utilisées et familières à de nombreuses personnes, ce qui facilite leur contact. Selon notre expérience, ces applications sont parmi les canaux les plus populaires utilisés par les dénonciateurs. Cette accessibilité encourage davantage d'individus à faire part de leurs préoccupations. Le logiciel de dénonciation de Phoenix n'est affilié à aucun service de messagerie instantanée et n'est pas intégré à votre compte de messagerie instantanée.",
        ),
        // German
        'de_DE' => array(
            'label' => "Instant Messaging (Anzeige)",
            'desc'  => "Zeigen Sie Ihre Instant-Messaging-Konten von Plattformen wie WhatsApp, Skype oder Telegram auf Ihrer dedizierten Website an. Instant Messaging-Apps werden von vielen Menschen weit verbreitet und sind ihnen vertraut, was es ihnen leicht macht, Kontakt aufzunehmen. Basierend auf unserer Erfahrung gehören diese Apps zu den beliebtesten Kanälen, die von Hinweisgebern verwendet werden. Diese Zugänglichkeit ermutigt mehr Personen, mit ihren Bedenken voranzukommen. Die Phoenix Whistleblowing-Software ist nicht mit einem Instant-Messaging-Dienst verbunden und integriert sich nicht in Ihr Instant-Messaging-Konto.",
        ),
        // Greek
        'el' => array(
            'label' => "Άμεση Ανταλλαγή Μηνυμάτων (Προβολή)",
            'desc'  => "Εμφανίστε τους λογαριασμούς σας στην άμεση ανταλλαγή μηνυμάτων από πλατφόρμες όπως το WhatsApp, το Skype ή το Telegram στην αφιερωμένη ιστοσελίδα σας. Οι εφαρμογές άμεσης ανταλλαγής μηνυμάτων χρησιμοποιούνται ευρέως και είναι γνωστές σε πολλούς ανθρώπους, κάτι που τους επιτρέπει να επικοινωνούν εύκολα. Βασιζόμενοι στην εμπειρία μας, αυτές οι εφαρμογές είναι ανάμεσα στα πιο δημοφιλή κανάλια που χρησιμοποιούν οι αναφέροντες παραβατικές πράξεις. Αυτή η προσβασιμότητα ενθαρρύνει περισσότερα άτομα να έρθουν μπροστά με τις ανησυχίες τους. Το λογισμικό Phoenix Whistleblowing δεν συνδέεται με καμία υπηρεσία άμεσης ανταλλαγής μηνυμάτων και δεν ενσωματώνεται με τον λογαριασμό σας στην άμεση ανταλλαγή μηνυμάτων.",
        ),
        // Hungarian
        'hu_HU' => array(
            'label' => "Közvetlen Üzenetek (Megjelenítés)",
            'desc'  => "Mutassa meg az Ön által használt közvetlen üzenetküldő szolgáltatásokat, mint például a WhatsApp, Skype vagy Telegram fiókjait a dedikált webhelyén. A közvetlen üzenetküldő szolgáltatások széles körben elterjedtek és ismertek sok ember számára, így jelentősen megkönnyítik a kommunikációt. Tapasztalataink alapján ezek az alkalmazások a legnépszerűbb csatornák közé tartoznak, amelyeket különböző informátorok használnak. Ez az elérhetőség ösztönzi az embereket, hogy megosszák aggodalmaikat. A Phoenix Whistleblowing szoftver nem kapcsolódik közvetlen üzenetküldő szolgáltatáshoz, és nem integrálódik közvetlen üzenetküldő fiókjával.",
        ),
        // Irish
        'ga_IE' => array(
            'label' => "Teachtaireachtaí Díreacha (Léiriú)",
            'desc'  => "Taispeáin do chuntais do sheirbhísí teachtaireachtaí díreacha ó réimse ábhair cosúil le WhatsApp, Skype nó Telegram ar do láithreán gréasáin tiomanta. Is cumhdach cumasach iad na seirbhísí teachtaireachtaí díreacha a úsáideann go forleathan agus is cáiliúla iad i measc mórán daoine, a chabhraíonn le cumarsáid. Bunaithe ar ár bhféin taithí, tá na cláir seo ar na bealaí cumarsáide is coitianta a úsáideann rialtóirí riosca. Cuirfidh an cumas seo chun cinn a thuilleadh daoine ag roinnt a gcuid imní. Níl bogearraí Phoenix Whistleblowing ag baint le haon sheirbhís teachtaireachta díreacha agus ní ghintear intíre i do chuntas teachtaireachtaí díreacha.",
        ),
        // Italian
        'it_IT' => array(
            'label' => "Messaggistica istantanea (Visualizzazione)",
            'desc'  => "Mostra i tuoi account di messaggistica istantanea da piattaforme come WhatsApp, Skype o Telegram sul tuo sito web dedicato. Le app di messaggistica istantanea sono ampiamente utilizzate e familiari per molte persone, rendendo facile per loro mettersi in contatto. Sulla base della nostra esperienza, queste app sono tra i canali più popolari usati dai whistleblower. Questa accessibilità incoraggia più persone a presentare le loro preoccupazioni. Il software di segnalazione di Phoenix Whistleblowing non è affiliato a nessun servizio di messaggistica istantanea e non si integra con il tuo account di messaggistica istantanea.",
        ),
        // Latvian
        'lv_LV' => array(
            'label' => "Uzreizējās ziņošanas (Parādīt)",
            'desc'  => "Parādiet savus uzreizējās ziņošanas kontus no platformām kā WhatsApp, Skype vai Telegram jūsu veltītajā tīmekļa vietnē. Uzreizējās ziņošanas lietotnes ir plaši izmantotas un pazīstamas daudziem cilvēkiem, tādēļ tiem ir viegli sazināties. Pamatojoties uz mūsu pieredzi, šīs lietotnes ir vieni no populārākajiem kanāliem, ko izmanto niddisčisti. Šāda pieejamība veicina vairāku personu izpausmi ar savām bažām. Phoenix Whistleblowing Software nav saistīts ar nevienu uzreizējās ziņošanas pakalpojumu un neintegrējas ar jūsu uzreizējās ziņošanas kontu.",
        ),
        // Lithuanian
        'lt_LT' => array(
            'label' => "Momentinės žinutės (Rodyti)",
            'desc'  => "Rodykite savo momentinės žinutės paskyras iš platformų, tokių kaip WhatsApp, Skype ar Telegram, savo skirtame tinklalapyje. Momentinės žinutės programos yra plačiai naudojamos ir daugeliui žmonių gerai pažįstamos, todėl jiems lengva kreiptis. Remiantis mūsų patirtimi, šios programos yra tarp populiariausių kanalų, kuriuos naudoja niddisčiai. Ši prieinamumas skatina daugiau žmonių pasisakyti dėl savo susirūpinimų. Phoenixi Whistleblowing tarkvara nėra susijusi su jokia momentinės žinutės paslauga ir nesuderinama su jūsų momentinės žinutės paskyra.",
        ),
        // Maltese
        'mt_MT' => array(
            'label' => "Messaġġi Diretti (Veduta)",
            'desc'  => "Uri l-kontijiet tiegħek għal servizzi ta’ messaġġi diretti mill-pjattaformi bħal WhatsApp, Skype jew Telegram fuq is-sit web dedikat tiegħek. Is-servizzi ta’ messaġġi diretti huma wiesgħin u magħrufin lil ħafna nies, li jagħtu fassilment fl-inkomunikazzjoni. Fuq bażi ta’ esperjenza tagħna, dawn l-applikazzjonijiet huma fost il-kanali aktar popolari użati mill-informatori. Din l-akċessibbiltà tispikka ħafna nies biex jaqsru l-preokkupazzjonijiet tagħhom. Il-proġett ta’ software tal-Phoenix Whistleblowing mhuwiex marbut ma’ servizz ta’ messaġġi diretti u ma jintegrawx mal-kont tiegħek ta’ messaġġi diretti.",
        ),
        // Norwegian Nynorsk
        'nb_NO' => array(
            'label' => "Direktemeldingar (Visning)",
            'desc'  => "Vis dine kontoar for direktemeldingstenester frå plattformar som WhatsApp, Skype eller Telegram på den dedikerte nettstaden din. Direktemeldingstenester er godt brukte og kjende blant mange menneske, noko som lettar kommunikasjonen. Basert på vår erfaring, er desse applikasjonane blant dei mest populære kanalane som vert nytta av varslarar. Denne tilgjengelegheita oppmodar fleire menneske til å dele sine bekymringar. Phoenix Whistleblowing programvaren er ikkje knytt til nokon direktemeldingsteneste og blir ikkje integrert med di direktemeldingskonto.",
        ),
        // Polish
        'pl_PL' => array(
            'label' => "Komunikatory (Wyświetlanie)",
            'desc'  => "Wyświetl swoje konta na usługach komunikatorów takich jak WhatsApp, Skype czy Telegram na dedykowanej stronie internetowej. Komunikatory są powszechnie używane i znane wielu ludziom, co ułatwia komunikację. Na podstawie naszego doświadczenia te aplikacje należą do najbardziej popularnych kanałów używanych przez informatorów. Dostępność ta zachęca więcej osób do dzielenia się swoimi obawami. Oprogramowanie Phoenix Whistleblowing nie jest powiązane z żadną usługą komunikatorów ani nie jest integrowane z Twoim kontem na komunikatory.",
        ),
        // Portuguese
        'pt_PT' => array(
            'label' => "Mensagens Diretas (Exibição)",
            'desc'  => "Exiba as suas contas de serviços de mensagens diretas de plataformas como WhatsApp, Skype ou Telegram no seu site dedicado. Os serviços de mensagens diretas são amplamente utilizados e conhecidos por muitas pessoas, facilitando a comunicação. Com base na nossa experiência, essas aplicações estão entre os canais mais populares usados por denunciantes. Essa acessibilidade encoraja mais pessoas a compartilharem suas preocupações. O software Phoenix Whistleblowing não está vinculado a nenhum serviço de mensagens diretas e não é integrado à sua conta de mensagens diretas.",
        ),
        // Romanian
        'ro_RO' => array(
            'label' => "Mesaje Directe (Vizualizare)",
            'desc'  => "Afișați conturile dvs. pentru serviciile de mesagerie directă de pe platforme precum WhatsApp, Skype sau Telegram pe site-ul dvs. dedicat. Serviciile de mesagerie directă sunt utilizate frecvent și cunoscute de multe persoane, facilitând comunicarea. Pe baza experienței noastre, aceste aplicații sunt printre cele mai populare canale folosite de persoanele care fac dezvăluiri. Această accesibilitate încurajează mai multe persoane să își împărtășească îngrijorările. Software-ul Phoenix Whistleblowing nu este legat de niciun serviciu de mesagerie directă și nu este integrat în contul dvs. de mesagerie directă.",
        ),
        // Slovak
        'sk_SK' => array(
            'label' => "Priame správy (Zobrazenie)",
            'desc'  => "Zobrazte svoje účty pre služby priamych správ z platformy ako WhatsApp, Skype alebo Telegram na vašej špecializovanej webovej stránke. Služby priamych správ sú široko používané a známe mnohým ľuďom, čo uľahčuje komunikáciu. Na základe našich skúseností patria tieto aplikácie medzi najpopulárnejšie kanály používané informátormi. Táto dostupnosť motivuje viac ľudí k zdieľaniu svojich obáv. Softvér Phoenix Whistleblowing nie je prepojený s žiadnou službou priamych správ a nie je integrovaný do vášho účtu pre priame správy.",
        ),
        // Slovenian
        'sl_SI' => array(
            'label' => "Neposredna sporočila (Prikaz)",
            'desc'  => "Prikažite svoje račune za storitve neposrednih sporočil s platform, kot so WhatsApp, Skype ali Telegram, na vaši posebni spletni strani. Storitve neposrednih sporočil so široko uporabljane in znane mnogim ljudem, kar olajšuje komunikacijo. Na podlagi naših izkušenj so te aplikacije med najbolj priljubljenimi kanali, ki jih uporabljajo razkritjevalci informacij. Ta dostopnost spodbuja več ljudi, da delijo svoje skrbi. Programska oprema Phoenix Whistleblowing ni povezana z nobeno storitvijo neposrednih sporočil in ni integrirana v vaš račun za neposredna sporočila.",
        ),
        // Spanish
        'es_ES' => array(
            'label' => "Mensajes Directos (Visualización)",
            'desc'  => "Muestra tus cuentas para los servicios de mensajería directa de plataformas como WhatsApp, Skype o Telegram en tu sitio web dedicado. Los servicios de mensajes directos son ampliamente utilizados y conocidos por muchas personas, lo que facilita la comunicación. Según nuestra experiencia, estas aplicaciones están entre los canales más populares utilizados por los denunciantes. Esta accesibilidad anima a más personas a compartir sus preocupaciones. El software Phoenix Whistleblowing no está vinculado a ningún servicio de mensajería directa ni se integra con tu cuenta de mensajes directos.",
        ),
        // Swedish
        'sv_SE' => array(
            'label' => "Direktmeddelanden (Visning)",
            'desc'  => "Visa dina konton för direktmeddelandetjänster från plattformar som WhatsApp, Skype eller Telegram på din dedikerade webbplats. Direktmeddelandetjänster är väl använda och kända av många människor, vilket underlättar kommunikationen. Baserat på vår erfarenhet är dessa appar bland de mest populära kanalerna som används av visselblåsare. Denna tillgänglighet uppmuntrar fler människor att dela sina bekymmer. Phoenix Whistleblowing-programvaran är inte kopplad till någon direktmeddelandetjänst och integreras inte med ditt konto för direktmeddelanden.",
         ),
    ),
    /**
     * Post mail
     */
    'postmail' => array(
        // English
        'en_US' => array( 
            'label' => "Postal address (Display)",
            'desc'  => "Some potential whistleblowers may not have access to or be comfortable with digital communication methods. Offering the option to report via postal mail ensures that these individuals can still participate in the whistleblowing process. Postal mail provides a way to report concerns while maintaining a high level of anonymity. Whistleblowers can send their reports without disclosing their identity, and organizations can take steps to protect their privacy.",
        ),
        // Euskara
        'eu_ES' => array(
            'label' => "Posta helbidea (Bistaratu)",
            'desc'  => "Zenbait denuntziatzaile potentzialak ez dituzte digitaleko komunikazio metodoak erabili edo bideratzeak ez dituzte onartzen. Posta bidez jakinaraztea aukeratzeak ziurtatzen du horiek oraindik denuntziatze prozesuan parte hartu dezakela. Posta bideak arazoak jakinarazteko bide bat ematen du anonimitate maila handi batean mantenduz. Denuntziatzaileek bere txostak bidal ditzakete ez zuten bere identitatea argitaratu beharrik eta erakundeek neurriak hartu ditzakete beren pribatutasuna babesteko.",
        ),
        // Bulgarian
        'bg_BG' => array(
            'label' => "Пощенски адрес (Показване)",
            'desc'  => "Някои потенциални информатори може да нямат достъп до или да се чувстват неудобно с цифрови методи за комуникация. Предлагането на възможност за докладване чрез пощенска пратка гарантира, че тези лица все още могат да участват в процеса на докладване. Пощенската пратка предоставя начин за докладване на притеснения, като запазва високо ниво на анонимност. Информаторите могат да изпратят своите доклади, без да разкриват своята идентичност, а организациите могат да предприемат стъпки за защита на тяхната поверителност.",
        ),
        // Croatian
        'hr' => array(
            'label' => "Poštanska adresa (Prikaz)",
            'desc'  => "Neke potencijalne prijavljivače možda nemaju pristup ili se ne osjećaju ugodno s digitalnim metodama komunikacije. Ponuda mogućnosti prijave putem poštanske pošiljke osigurava da ovi pojedinci i dalje mogu sudjelovati u procesu prijavljivanja. Poštanska pošiljka pruža način za prijavu problema uz održavanje visokog stupnja anonimnosti. Prijavljenici mogu poslati svoje izvještaje bez otkrivanja svoje identiteta, a organizacije mogu poduzeti korake kako bi zaštitile njihovu privatnost.",
        ),
        // Czech
        'cs_CZ' => array(
            'label' => "Poštovní adresa (Zobrazení)",
            'desc'  => "Někteří potenciální whistlebloweri nemusí mít přístup nebo se cítit pohodlně s digitálními komunikačními metodami. Nabídnutí možnosti hlášení prostřednictvím poštovní pošty zajišťuje, že tito jednotlivci stále mohou participovat na procesu whistleblowingu. Pošta poskytuje způsob, jak hlásit obavy zachovávající vysokou úroveň anonymity. Whistlebloweři mohou odesílat své zprávy bez uvedení své identity a organizace mohou podniknout kroky k ochraně jejich soukromí.",
        ),
        // Danish
        'da_DK' => array(
            'label' => "Postadresse (display)",
            'desc'  => "Nogle potentielle whistleblowere har måske ikke adgang til eller er fortrolige med digitale kommunikationsmetoder. At tilbyde muligheden for at rapportere via post sikrer, at disse personer stadig kan deltage i whistleblowing-processen. Post tilbyder en måde at rapportere bekymringer på og samtidig opretholde et højt niveau af anonymitet. Whistleblowere kan sende deres rapporter uden at afsløre deres identitet, og organisationer kan tage skridt til at beskytte deres privatliv.",
        ),
        // Dutch
        'nl_NL' => array(
            'label' => "Postadres (Weergave)",
            'desc'  => "Sommige potentiële klokkenluiders hebben mogelijk geen toegang tot of voelen zich niet op hun gemak bij digitale communicatiemethoden. Door de optie te bieden om via post te melden, wordt ervoor gezorgd dat deze individuen nog steeds kunnen deelnemen aan het klokkenluidersproces. Post biedt een manier om zorgen te melden met behoud van een hoog niveau van anonimiteit. Klokkenluiders kunnen hun rapporten verzenden zonder hun identiteit prijs te geven, en organisaties kunnen stappen ondernemen om hun privacy te beschermen.",
        ),
        // Estonian
        'et' => array(
            'label' => "Postiaadress (Kuva)",
            'desc'  => "Mõned potentsiaalsed whistleblowersid ei pruugi omada juurdepääsu või olla mugavad digitaalsete suhtlusmeetoditega. Võimalus teatada posti teel tagab, et need isikud saavad siiski osaleda whistleblowing protsessis. Posti teel saab esitada kaebusi, samal ajal säilitades kõrge taseme anonüümsust. Whistleblowerid saavad saata oma aruanded ilma oma identiteeti avaldamata ning organisatsioonid saavad võtta meetmeid nende privaatsuse kaitseks.",
        ),
        // Finnish
        'fi' => array(
            'label' => "Postiosoite (Näyttö)",
            'desc'  => "Osa potentiaalisista ilmiantajista saattaa olla ilman pääsyä tai ei ehkä ole mukava digitaalisten viestintämenetelmien kanssa. Mahdollisuus raportoida postitse varmistaa, että nämä yksilöt voivat silti osallistua ilmiantoprosessiin. Posti tarjoaa tavan raportoida huolia ylläpitäen korkeaa anonymiteettitasoa. Ilmiantajat voivat lähettää raporttinsa ilman että heidän identiteettinsä paljastuu, ja organisaatiot voivat toteuttaa toimenpiteitä heidän yksityisyytensä suojelemiseksi.",
        ),
        // French
        'fr_FR' => array(
            'label' => "Adresse postale (Affichage)",
            'desc'  => "Certains dénonciateurs potentiels peuvent ne pas avoir accès ou être à l'aise avec les méthodes de communication numériques. Offrir la possibilité de signaler par courrier postal garantit que ces individus peuvent toujours participer au processus de dénonciation. Le courrier postal offre un moyen de signaler des problèmes tout en maintenant un haut niveau d'anonymat. Les dénonciateurs peuvent envoyer leurs rapports sans divulguer leur identité, et les organisations peuvent prendre des mesures pour protéger leur vie privée.",
        ),
        // German
        'de_DE' => array(
            'label' => "Postanschrift (Anzeige)",
            'desc'  => "Einige potenzielle Hinweisgeber haben möglicherweise keinen Zugang zu digitalen Kommunikationsmethoden oder fühlen sich damit nicht wohl. Die Möglichkeit, per Post zu melden, gewährleistet, dass diese Personen dennoch am Hinweisgebersystem teilnehmen können. Die Post bietet eine Möglichkeit, Bedenken zu melden, während ein hohes Maß an Anonymität gewahrt bleibt. Hinweisgeber können ihre Berichte ohne Offenlegung ihrer Identität senden, und Organisationen können Maßnahmen zum Schutz ihrer Privatsphäre ergreifen.",
        ),
        // Greek
        'el' => array(
            'label' => "Ταχυδρομική διεύθυνση (Προβολή)",
            'desc'  => "Ορισμένοι δυνητικοί αναφέροντες παραβατικές πράξεις ενδέχεται να μην έχουν πρόσβαση ή να νιώθουν άνετα με τις ψηφιακές μεθόδους επικοινωνίας. Η προσφορά της επιλογής για αναφορά μέσω ταχυδρομείου εξασφαλίζει ότι αυτά τα άτομα μπορούν ακόμα να συμμετέχουν στη διαδικασία αναφοράς παραβατικών πράξεων. Το ταχυδρομείο παρέχει έναν τρόπο για να αναφερθούν ανησυχίες διατηρώντας ένα υψηλό επίπεδο ανωνυμίας. Οι αναφέροντες παραβατικές πράξεις μπορούν να στείλουν τα αναφορικά τους χωρίς να αποκαλύψουν την ταυτότητά τους, και οι οργανισμοί μπορούν να λάβουν μέτρα για να προστατεύσουν την ιδιωτικότητά τους.",
        ),
        // Hungarian
        'hu_HU' => array(
            'label' => "Postai cím (Megjelenítés)",
            'desc'  => "Néhány potenciális bejelentőnek lehet, hogy nincs hozzáférése vagy nem érzi magát kényelmesen a digitális kommunikációs módszerekkel. Az postai levél lehetőségének biztosítása azt jelenti, hogy ezek az egyének még mindig részt vehetnek a bejelentési folyamatban. Az postai levél egy módja arra, hogy problémáikat jelentsék, miközben magas szintű anonimitást biztosítanak. A bejelentők küldhetik bejelentéseiket anélkül, hogy azonosítójukat felfednék, és a szervezetek intézkedéseket tehetnek a magánéletük védelme érdekében.",
        ),
        // Irish
        'ga_IE' => array(
            'label' => "Seoladh Poist (Taispeáint)",
            'desc'  => "D'fhéadfadh cuid de na heitricithe is féidir gan rochtain nó gan bheith sásta le modhanna cumarsáide digiteacha. Trí rogha a thabhairt chun tuairisc a dhéanamh trí phostáil, cinntítear go bhféadfaidh na daoine seo fós páirt a ghlacadh i bpróiseas na heitricithe. Soláthraíonn an phostáil bealach chun imní a thuarascáil agus ar an am céanna aon leibhéal ardachta a choinneáil ar an anaimníocht. Féadfaidh na heitricithe a gcuid tuairiscí a sheoladh gan a n-ainmneacha a fhoilsiú, agus is féidir le heagraíochtaí bearta a ghlacadh chun a gcuid príobháideachta a chosaint.",
        ),
        // Italian
        'it_IT' => array(
            'label' => "Indirizzo postale (Visualizzazione)",
            'desc'  => "Alcuni potenziali whistleblower potrebbero non avere accesso o essere a proprio agio con i metodi di comunicazione digitale. Offrire l'opzione di segnalare via correio postal garantisce che queste persone possano comunque partecipare al processo di segnalazione. La posta postale fornisce un modo per segnalare preoccupazioni mantenendo un alto livello di anonimato. I whistleblower possono inviare le loro segnalazioni senza divulgarle la propria identità, e le organizzazioni possono prendere misure per proteggere la loro privacy.",
        ),
        // Latvian
        'lv_LV' => array(
            'label' => "Pasta adrese (Rodyti)",
            'desc'  => "Dažiem potenciāliem niddisċistiem var nebūt pieejama vai tiem var nebūt ērti lietot digitālās komunikācijas metodes. Siūlant galimybę pranešti paštu, užtikrinama, kad šios asmenybės vis tiek galės dalyvauti niddisčių procese. Paštas suteikia būdą pranešti susirūpinimais, išlaikant aukštą anonimiškumo lygį. Niddisčiai gali siųsti savo pranešimus, neatskleisdami savo tapatybės, o organizacijos gali imtis priemonių, kad apsaugotų jų privatumą.",
        ),
        // Lithuanian
        'lt_LT' => array(
            'label' => "Pašto adresas (Rodyti)",
            'desc'  => "Nekateri potencialni razkrinkovalci morda nimajo dostopa ali se ne počutijo udobno s digitalnimi komunikacijskimi metodami. Ponudba možnosti za poročanje prek poštne pošiljke zagotavlja, da lahko ti posamezniki še vedno sodelujejo v postopku razkrivanja. Pošta zagotavlja način za poročanje o skrbih ob ohranjanju visoke ravni anonimnosti. Razkrinkovalci lahko pošljejo svoja poročila, ne da bi razkrili svojo identiteto, organizacije pa lahko sprejmejo korake za zaščito njihove zasebnosti.",
        ),
        // Maltese
        'mt_MT' => array(
            'label' => "Indirizz Pożtali (Uriżża)",
            'desc'  => "Xi eżponenti potenzjali ta' whistleblowing jistgħu ma jkollhomx aċċess jew ma jaħsebx liema mezzijiet ta' komunikazzjoni diġitali. L-offerta tal-għażla li jiġi rapportat permezz tal-posta pożżibbila tassigura li dawn l-individwi jistgħu għadhom jieħdu sehem fil-proċess ta' whistleblowing. Il-posta tipprovdi mod kif jingħataw l-ħerqa permezz tal-messaġġi, waqt li tinżamm livell għoli ta' anonimità. L-eżponenti ta' whistleblowing jistgħu jibagħtu l-ħerqa tagħhom mingħajr ma jidħlu l-identità tagħhom, u l-organizzazzjonijiet jistgħu jieħdu miżuri biex iġġibdu l-protezzjoni tal-privatezza tagħhom.",
        ),
        // Norwegian Nynorsk
        'nb_NO' => array(
            'label' => "Postadresse (visning)",
            'desc'  => "Noen potensielle varslere har kanskje ikke tilgang til eller er komfortable med digitale kommunikasjonsmetoder. Å tilby muligheten til å rapportere via post sikrer at disse personene fortsatt kan delta i varslingsprosessen. Postpost gir en måte å rapportere bekymringer på og samtidig opprettholde et høyt nivå av anonymitet. Varslere kan sende rapportene sine uten å avsløre identiteten sin, og organisasjoner kan ta skritt for å beskytte personvernet deres.",
        ),
        // Polish
        'pl_PL' => array(
            'label' => "Adres pocztowy (Wyświetlanie)",
            'desc'  => "Niektórzy potencjalni informatorzy mogą nie mieć dostępu lub nie czuć się komfortowo z cyfrowymi metodami komunikacji. Oferowanie opcji zgłaszania za pośrednictwem poczty zapewnia, że ​​te osoby mogą wciąż uczestniczyć w procesie zgłaszania. Poczta zapewnia sposób zgłaszania obaw przy zachowaniu wysokiego poziomu anonimowości. Informatorzy mogą przesyłać swoje raporty, nie ujawniając swojej tożsamości, a organizacje mogą podjąć działania w celu ochrony ich prywatności.",
        ),
        // Portuguese
        'pt_PT' => array(
            'label' => "Endereço Postal (Visualização)",
            'desc'  => "Alguns potenciais denunciantes podem não ter acesso ou não se sentirem confortáveis com métodos de comunicação digital. Oferecer a opção de relatar por correio postal garante que essas pessoas possam ainda assim participar no processo de denúncia. O correio postal proporciona uma forma de reportar preocupações mantendo um elevado nível de anonimato. Os denunciantes podem enviar os seus relatos sem revelar a sua identidade, e as organizações podem tomar medidas para proteger a sua privacidade.",
        ),
        // Romanian
        'ro_RO' => array(
            'label' => "Adresă poștală (Vizualizare)",
            'desc'  => "Unii potențiali denunțători ar putea să nu aibă acces sau să nu se simtă confortabil cu metodele de comunicare digitală. Oferirea opțiunii de a raporta prin poștă asigură că acești indivizi pot totuși să participe la procesul de denunțare. Poșta oferă o modalitate de a raporta îngrijorările menținând un nivel înalt de anonimat. Denunțătorii pot trimite rapoartele lor fără a-și dezvălui identitatea, iar organizațiile pot lua măsuri pentru a proteja confidențialitatea acestora.",
        ),
        // Slovak
        'sk_SK' => array(
            'label' => "Poštová adresa (Zobraziť)",
            'desc'  => "Niektorí potenciálni oznamovatelia môžu nemusieť mať prístup alebo sa necítiť pohodlne s digitálnymi metódami komunikácie. Ponúknutie možnosti hlásenia prostredníctvom poštového úradu zabezpečuje, že tieto osoby môžu napriek tomu zúčastniť sa na procese hlásenia. Pošta poskytuje spôsob, ako hlásiť obavy pri zachovaní vysokého stupňa anonymity. Oznamovatelia môžu posielať svoje správy bez odhalenia svojej identity a organizácie môžu prijať opatrenia na ochranu ich súkromia.",
        ),
        // Slovenian
        'sl_SI' => array(
            'label' => "Poštni naslov (Prikaz)",
            'desc'  => "Nekateri potencialni prijavitelji morda nimajo dostopa do ali se ne počutijo udobno s digitalnimi metodami komuniciranja. Možnost prijave po pošti zagotavlja, da lahko ti posamezniki kljub temu sodelujejo v postopku prijave. Pošta ponuja način za prijavo skrbi ob ohranjanju visoke ravni anonimnosti. Prijavitelji lahko pošljejo svoja poročila brez razkrivanja svoje identitete, organizacije pa lahko sprejmejo ukrepe za zaščito njihove zasebnosti.",
        ),
        // Spanish
        'es_ES' => array(
            'label' => "Dirección Postal (Visualización)",
            'desc'  => "Algunos posibles informantes pueden no tener acceso o no sentirse cómodos con los métodos de comunicación digital. Ofrecer la opción de informar por correo postal asegura que estas personas aún puedan participar en el proceso de denuncia. El correo postal proporciona una manera de informar preocupaciones manteniendo un alto nivel de anonimato. Los informantes pueden enviar sus informes sin revelar su identidad, y las organizaciones pueden tomar medidas para proteger su privacidad.",
        ),
        // Swedish
        'sv_SE' => array(
            'label' => "Postadress (Visning)",
            'desc'  => "Vissa potentiella visselblåsare kan sakna tillgång till eller känna sig obekväma med digitala kommunikationsmetoder. Att erbjuda möjligheten att rapportera via post säkerställer att dessa individer fortfarande kan delta i rapporteringsprocessen. Posten erbjuder ett sätt att rapportera oro med bibehållen hög anonymitetsnivå. Visselblåsare kan skicka sina rapporter utan att avslöja sin identitet, och organisationer kan vidta åtgärder för att skydda deras integritet.",
        ),
    ),
    /**
     * chat
     */
    'chat' => array(
        // English
        'en_US' => array(
            'label' => "Online Chat",
            'desc'  => "Improve the whistleblower experience with our secure online chat, designed and powered by Phoenix Whistleblowing Software. Our chat functionality includes dedicated rooms for seamless and confidential communication. It is user-friendly and convenient, requiring minimal effort to start a conversation. Like other digital channels, online chat maintains the whistleblower's anonymity, protecting their identity and ensuring their safety. It also provides transcripts of conversations, creating a clear and documented record of the report. Include a dedicated chat room for each reporting pipeline.",
        ),
        // Euskara
        'eu_ES' => array(
            'label' => "Txat Online",
            'desc'  => "Hobetu denuntziatzaileen esperientzia gure seguruko online txatekin, Phoenix Whistleblowing Softwarek diseinatua eta indarrean. Gure txatearen funtzionalitateak atxikitako gela batzuk ditu, jarrera eta konfidentzialtasun komunikazioa emateko. Erabiltzaile-enganagarri eta erabilgarri da, ez du berezko esfortzurik elkarrizketa hasi ahal izateko. Beste edozein digitalen alde bezala, online txateak denuntziatzaileen anonimatua mantentzen du, haien identitatea babestuz eta haien segurtasuna bermatuz. Hala ere, elkarrizketen transkripzioak ere ematen ditu, argia eta dokumentatua denuntziaren erregistroa sortuz. Incluídu atxikitako txate gela bakoitzarentzat denuntziaren ardatzak.",
        ),
        // Bulgarian
        'bg_BG' => array(
            'label' => "Онлайн чат",
            'desc'  => "Подобрете преживяването на информатора с нашия сигурен онлайн чат, създаден и захранван от Phoenix Whistleblowing Software. Функционалността на нашия чат включва отделни стаи за безпроблемна и поверителна комуникация. Той е лесен за използване и удобен, изисква минимален труд, за да започне разговор. Както и другите цифрови канали, онлайн чатът поддържа анонимността на информатора, защитавайки неговата идентичност и гарантирайки неговата безопасност. Той също така предоставя транскрипции на разговорите, създавайки ясен и документиран запис на доклада. Включете отделна стая за чат за всеки канал на докладване.",
        ),
        // Croatian
        'hr' => array(
            'label' => "Online Chat",
            'desc'  => "Poboljšajte iskustvo prijavljivača s našim sigurnim online chatom, dizajniranim i napajanom od strane Phoenix Whistleblowing Softwarea. Naša funkcionalnost chata uključuje posvećene sobe za besprijekornu i povjerljivu komunikaciju. Jednostavno je za korištenje i praktično, zahtijeva minimalan napor za započinjanje razgovora. Kao i drugi digitalni kanali, online chat održava anonimnost prijavljivača, štiteći njihov identitet i osiguravajući njihovu sigurnost. Također pruža transkripte razgovora, stvarajući jasan i dokumentiran zapis izvješća. Uključite posvećenu chat sobu za svaki kanal prijavljivanja.",
        ),
        // Czech
        'cs_CZ' => array(
            'label' => "Online Chat",
            'desc'  => "Vylepšete zkušenost whistleblowerů s naší bezpečnou online chatovací funkcí, navrženou a poháněnou softwarem Phoenix Whistleblowing. Naše chatovací funkce zahrnuje vyhrazené místnosti pro plynulou a důvěrnou komunikaci. Je uživatelsky přívětivá a pohodlná a vyžaduje minimální úsilí pro započetí konverzace. Stejně jako ostatní digitální kanály, online chat zachovává anonymitu whistleblowera, chrání jeho identitu a zajišťuje jeho bezpečnost. Poskytuje také transkripty rozhovorů, vytvářející jasný a zdokumentovaný záznam o hlášení. Zahrňte vyhrazenou místnost pro chat pro každý kanál hlášení.",
        ),
        // Danish
        'da_DK' => array(
            'label' => "Online Chat",
            'desc'  => "Forbedre whistleblower-oplevelsen med vores sikre online chat, designet og drevet af Phoenix Whistleblowing Software. Vores chatfunktionalitet inkluderer dedikerede rum til problemfri og fortrolig kommunikation. Det er brugervenligt og bekvemt og kræver minimal indsats for at starte en samtale. Som andre digitale kanaler opretholder online chat whistleblowerens anonymitet, beskytter deres identitet og sikrer deres sikkerhed. Det giver også transskriptioner af samtaler, hvilket skaber en klar og dokumenteret registrering af rapporten. Medtag et dedikeret chatrum for hver rapporteringspipeline.",
        ),
        // Dutch
        'nl_NL' => array(
            'label' => "Online Chat",
            'desc'  => "Verbeter de klokkenluiderservaring met onze beveiligde online chat, ontworpen en aangedreven door Phoenix Klokkenluidersoftware. Onze chatfunctionaliteit omvat toegewijde ruimtes voor naadloze en vertrouwelijke communicatie. Het is gebruiksvriendelijk en handig, waarbij minimale inspanning nodig is om een gesprek te starten. Net als andere digitale kanalen behoudt online chat de anonimiteit van de klokkenluider, beschermt het hun identiteit en zorgt het voor hun veiligheid. Het biedt ook transcripten van gesprekken, waardoor een duidelijk en gedocumenteerd overzicht van het rapport wordt gecreëerd. Voeg een toegewijde chatruimte toe voor elke rapportagepijplijn.",
        ),
        // Estonian
        'et' => array(
            'label' => "Online Chat",
            'desc'  => "Parandage whistleblowerite kogemust meie turvalise veebivestlusega, mis on kavandatud ja toidetud Phoenixi Whistleblowing tarkvaraga. Meie vestlusfunktsioon sisaldab eraldatud ruume sujuvaks ja konfidentsiaalseks suhtluseks. See on kasutajasõbralik ja mugav ning nõuab vestluse alustamiseks minimaalset pingutust. Nagu teised digitaalsed kanalid, hoiab veebivestlus whistlebloweri anonüümsust, kaitstes nende identiteeti ja tagades nende turvalisuse. See pakub ka vestluste transkripte, luues selge ja dokumenteeritud aruande raportist. Lisage igale teatamiskanalile eraldatud vestlusruum.",
        ),
        // Finnish
        'fi' => array(
            'label' => "Online Chat",
            'desc'  => "Paranna ilmiantajan kokemusta turvallisella verkkokeskustelulla, suunniteltu ja voimakkaasti Phoenixin ilmiantosovelluksen avulla. Keskustelutoimintomme sisältää omistetut huoneet saumattomaan ja luottamukselliseen viestintään. Se on käyttäjäystävällinen ja kätevä, vaati vähäistä vaivaa keskustelun aloittamiseksi. Kuten muutkin digitaaliset kanavat, verkkokeskustelu säilyttää ilmiantajan anonymiteetin, suojaten heidän identiteettiään ja varmistaen heidän turvallisuutensa. Se tarjoaa myös keskustelun litteroinnit, luoden selkeän ja dokumentoidun raportin. Sisällytä omistettu keskusteluhuone jokaiseen raportointiputkeen.",
        ),
        // French
        'fr_FR' => array(
            'label' => "Chat en ligne",
            'desc'  => "Améliorez l'expérience des dénonciateurs avec notre chat en ligne sécurisé, conçu et alimenté par le logiciel de dénonciation de Phoenix. Notre fonctionnalité de chat comprend des salles dédiées pour une communication fluide et confidentielle. Il est convivial et pratique, nécessitant un effort minimal pour démarrer une conversation. Comme d'autres canaux numériques, le chat en ligne maintient l'anonymat du dénonciateur, protégeant leur identité et assurant leur sécurité. Il fournit également des transcriptions des conversations, créant un enregistrement clair et documenté du rapport. Incluez une salle de chat dédiée pour chaque canal de signalement.",
        ),
        // German
        'de_DE' => array(
            'label' => "Online-Chat",
            'desc'  => "Verbessern Sie die Erfahrung der Hinweisgeber mit unserem sicheren Online-Chat, der von der Phoenix Whistleblowing-Software entwickelt und betrieben wird. Unsere Chat-Funktionalität umfasst dedizierte Räume für nahtlose und vertrauliche Kommunikation. Es ist benutzerfreundlich und praktisch und erfordert nur minimale Anstrengungen, um ein Gespräch zu beginnen. Wie andere digitale Kanäle bewahrt der Online-Chat die Anonymität des Hinweisgebers, schützt deren Identität und gewährleistet deren Sicherheit. Es bietet auch Transkriptionen von Gesprächen, wodurch ein klarer und dokumentierter Bericht entsteht. Inkludieren Sie für jede Melde-Pipeline einen dedizierten Chatraum.",
        ),
        // Greek
        'el' => array(
            'label' => "Συνομιλία μέσω Διαδικτύου",
            'desc'  => "Βελτιώστε την εμπειρία του καταγγέλλοντος με την ασφαλή διαδικτυακή συνομιλία μας, σχεδιασμένη και υποστηριζόμενη από το λογισμικό καταγγελιών Phoenix. Η λειτουργικότητα της συνομιλίας μας περιλαμβάνει αφιερωμένα δωμάτια για απρόσκοπτη και εμπιστευτική επικοινωνία. Είναι φιλική προς το χρήστη και βολική, απαιτώντας ελάχιστη προσπάθεια για την έναρξη μιας συνομιλίας. Όπως και άλλα ψηφιακά κανάλια, η διαδικτυακή συνομιλία διατηρεί την ανωνυμία του καταγγέλλοντος, προστατεύοντας την ταυτότητά τους και διασφαλίζοντας την ασφάλειά τους. Παρέχει επίσης απομαγνητοφωνήσεις των συνομιλιών, δημιουργώντας μια σαφή και τεκμηριωμένη καταγραφή της αναφοράς. Συμπεριλάβετε ένα αφιερωμένο δωμάτιο συνομιλίας για κάθε κανάλι αναφοράς.",
        ),
        // Hungarian
        'hu_HU' => array(
            'label' => "Online Chat",
            'desc'  => "Javítsa a bejelentők élményét biztonságos online chatünkkel, amelyet a Phoenix Whistleblowing Software tervezett és működtet. Chat-funkcionalitásunk dedikált szobákat tartalmaz a zökkenőmentes és bizalmas kommunikáció érdekében. Felhasználóbarát és kényelmes, minimális erőfeszítést igényel a beszélgetés megkezdéséhez. Mint más digitális csatornák, az online chat is fenntartja a bejelentő anonimitását, védve identitásukat és biztosítva biztonságukat. Beszélgetések átiratait is biztosítja, így világos és dokumentált jelentést készít. Tartalmazzon egy dedikált chatszobát minden jelentési csatornához.",
        ),
        // Irish
        'ga_IE' => array(
            'label' => "Comhrá ar líne",
            'desc'  => "Feabhsaigh taithí na n-eitricithe leis an gcaidreamh ar líne árachasach againne, a dhearadh agus a bhfuinneogú ag Phoenix Whistleblowing Software. Cuimsíonn ár gcaidreamh gnéithe ar leithligh do chomhrá álainn agus rúnda. Tá sé úsáideach agus éasca le húsáid, ag teastáil mínas saothair chun comhrá a thosú. Cosúil le canálaí digiteacha eile, coimeádann an chomhrá ar líne an anaimníocht agus an féinmharú na heitricithe, a gceannais a n-ainmneacha agus a n-sábháilteacht. Chomh maith leis sin, cuireann sé trascripts de chomhráite ar fáil, ag cruthú taifid shoiléir agus cáipéisithe den tuairisc. Cuir isteach seomra comhrá dírithe do gach bhiolar tuairiscithe.",
        ),
        // Italian
        'it_IT' => array(
            'label' => "Chat Online",
            'desc'  => "Migliora l'esperienza del segnalante con la nostra chat online sicura, progettata e alimentata dal Phoenix Whistleblowing Software. La nostra funzionalità di chat include stanze dedicate per una comunicazione fluida e confidenziale. È user-friendly e conveniente, richiedendo uno sforzo minimo per avviare una conversazione. Come altri canali digitali, la chat online mantiene l'anonimato del segnalante, proteggendo la loro identità e garantendo la loro sicurezza. Fornisce anche trascrizioni delle conversazioni, creando un registro chiaro e documentato del rapporto. Includi una stanza di chat dedicata per ogni pipeline di segnalazione.",
        ),
        // Latvian
        'lv_LV' => array(
            'label' => "Tiešsaistes Čats",
            'desc'  => "Uzlabojiet ziņotāja pieredzi ar mūsu drošu tiešsaistes čatu, kuru izstrādājusi un uztur Phoenix Whistleblowing Software. Mūsu čata funkcionalitāte ietver īpašas telpas nevainojamai un konfidenciālai saziņai. Tā ir lietotājam draudzīga un ērta, prasa minimālas pūles, lai sāktu sarunu. Tāpat kā citi digitālie kanāli, tiešsaistes čats saglabā ziņotāja anonimitāti, aizsargājot viņu identitāti un nodrošinot viņu drošību. Tāpat tas nodrošina sarunu transkripcijas, radot skaidru un dokumentētu ziņojuma ierakstu. Iekļaujiet īpašu čata telpu katram ziņošanas kanālam.",
        ),
        // Lithuanian
        'lt_LT' => array(
            'label' => "Internetinis Pokalbis",
            'desc'  => "Pagerinkite pranešėjo patirtį su mūsų saugiu internetiniu pokalbiu, sukurtu ir valdomu Phoenix Whistleblowing Software. Mūsų pokalbių funkcionalumas apima specialias patalpas sklandžiam ir konfidencialiam bendravimui. Jis yra naudotojui patogus ir patogus, reikalaujantis minimalių pastangų norint pradėti pokalbį. Kaip ir kiti skaitmeniniai kanalai, internetinis pokalbis palaiko pranešėjo anonimiškumą, apsaugodamas jų tapatybę ir užtikrinant jų saugumą. Taip pat teikia pokalbių stenogramas, sukuriant aiškų ir dokumentuotą ataskaitos įrašą. Įtraukite specialią pokalbių patalpą kiekvienam pranešimų kanalui.",
        ),
        // Maltese
        'mt_MT' => array(
            'label' => "Chat Online",
            'desc'  => "Itejjeb l-esperjenza tal-whistleblower bl-chat online sikur tagħna, iddisinjat u mmexxi mill-Phoenix Whistleblowing Software. Il-funzjonalità tal-chat tagħna tinkludi kmamar iddedikati għall-komunikazzjoni bla xkiel u kunfidenzjali. Huwa faċli għall-utent u konvenjenti, u jeħtieġ ftit sforz biex tibda konverżazzjoni. Bħal kanali diġitali oħra, il-chat online jżomm l-anonimità tal-whistleblower, jipproteġi l-identità tagħhom u jiżgura s-sigurtà tagħhom. Jipprovdi wkoll traskrizzjonijiet tal-konversazzjonijiet, joħloq rekord ċar u dokumentat tar-rapport. Inkludi kamra tal-chat iddedikata għal kull pipeline tar-rapport.",
        ),
        // Norwegian Nynorsk
        'nb_NO' => array(
            'label' => "Online Chat",
            'desc'  => "Forbedre varslernes opplevelse med vår sikre online chat, designet og drevet av Phoenix Whistleblowing Software. Chatfunksjonaliteten vår inkluderer dedikerte rom for sømløs og konfidensiell kommunikasjon. Den er brukervennlig og praktisk, og krever minimal innsats for å starte en samtale. Som andre digitale kanaler opprettholder online chat varslernes anonymitet, beskytter identiteten deres og sikrer sikkerheten deres. Den gir også transkripsjoner av samtaler, og skaper en klar og dokumentert rapport. Inkluder et dedikert chatte rom for hver rapporteringsrørledning.",
        ),
        // Polish
        'pl_PL' => array(
            'label' => "Czat Online",
            'desc'  => "Popraw doświadczenia informatora dzięki naszemu bezpiecznemu czatowi online, zaprojektowanemu i obsługiwanemu przez Phoenix Whistleblowing Software. Nasza funkcjonalność czatu obejmuje dedykowane pokoje do płynnej i poufnej komunikacji. Jest przyjazny dla użytkownika i wygodny, wymagający minimalnego wysiłku, aby rozpocząć rozmowę. Jak inne kanały cyfrowe, czat online utrzymuje anonimowość informatora, chroniąc ich tożsamość i zapewniając ich bezpieczeństwo. Zapewnia również transkrypcje rozmów, tworząc jasny i udokumentowany zapis raportu. Zawiera dedykowany pokój czatu dla każdej linii raportowania.",
        ),
        // Portuguese
        'pt_PT' => array(
            'label' => "Chat Online",
            'desc'  => "Melhore a experiência do denunciante com nosso chat online seguro, projetado e alimentado pelo Phoenix Whistleblowing Software. Nossa funcionalidade de chat inclui salas dedicadas para comunicação contínua e confidencial. É fácil de usar e conveniente, exigindo um esforço mínimo para iniciar uma conversa. Como outros canais digitais, o chat online mantém o anonimato do denunciante, protegendo sua identidade e garantindo sua segurança. Ele também fornece transcrições das conversas, criando um registro claro e documentado do relatório. Inclua uma sala de chat dedicada para cada pipeline de relatório.",
        ),
        // Romanian
        'ro_RO' => array(
            'label' => "Chat Online",
            'desc'  => "Îmbunătățiți experiența avertizorului cu chat-ul nostru online securizat, proiectat și alimentat de Phoenix Whistleblowing Software. Funcționalitatea chat-ului nostru include camere dedicate pentru comunicare fluidă și confidențială. Este ușor de utilizat și convenabil, necesită un efort minim pentru a începe o conversație. La fel ca alte canale digitale, chat-ul online menține anonimatul avertizorului, protejând identitatea acestora și asigurând siguranța lor. De asemenea, oferă transcrieri ale conversațiilor, creând un raport clar și documentat. Includeți o cameră de chat dedicată pentru fiecare canal de raportare.",
        ),
        // Slovak
        'sk_SK' => array(
            'label' => "Online Chat",
            'desc'  => "Vylepšite skúsenosti oznamovateľa s naším zabezpečeným online chatom, ktorý je navrhnutý a podporovaný softvérom Phoenix Whistleblowing. Naša chatovacia funkcia zahŕňa vyhradené miestnosti pre bezproblémovú a dôvernú komunikáciu. Je užívateľsky prívetivý a pohodlný, vyžadujúci minimálne úsilie na začatie rozhovoru. Rovnako ako iné digitálne kanály, online chat zachováva anonymitu oznamovateľa, chráni ich identitu a zaisťuje ich bezpečnosť. Poskytuje tiež prepisy rozhovorov, čím vytvára jasný a zdokumentovaný záznam o hlásení. Zahrňte vyhradenú chatovaciu miestnosť pre každú hlásenú trasu.",
        ),
        // Slovenian
        'sl_SI' => array(
            'label' => "Spletni Klepet",
            'desc'  => "Izboljšajte izkušnjo žvižgača z našim varnim spletnim klepetom, ki ga je zasnovala in poganja programska oprema Phoenix Whistleblowing. Naša funkcionalnost klepeta vključuje namenske sobe za nemoteno in zaupno komunikacijo. Je uporabniku prijazna in priročna, saj zahteva minimalen trud za začetek pogovora. Tako kot drugi digitalni kanali spletni klepet ohranja anonimnost žvižgača, ščiti njihovo identiteto in zagotavlja njihovo varnost. Prav tako zagotavlja prepise pogovorov, kar ustvarja jasen in dokumentiran zapis poročila. Vključite namenski klepet za vsako poročanje.",
        ),
        // Spanish
        'es_ES' => array(
            'label' => "Chat En Línea",
            'desc'  => "Mejore la experiencia del denunciante con nuestro chat en línea seguro, diseñado y alimentado por Phoenix Whistleblowing Software. Nuestra funcionalidad de chat incluye salas dedicadas para una comunicación fluida y confidencial. Es fácil de usar y conveniente, requiriendo un esfuerzo mínimo para iniciar una conversación. Al igual que otros canales digitales, el chat en línea mantiene el anonimato del denunciante, protegiendo su identidad y garantizando su seguridad. También proporciona transcripciones de las conversaciones, creando un registro claro y documentado del informe. Incluya una sala de chat dedicada para cada canal de informes.",
        ),
        // Swedish
        'sv_SE' => array(
            'label' => "Onlinechatt",
            'desc'  => "Förbättra visselblåsarens upplevelse med vår säkra onlinechatt, designad och driven av Phoenix Whistleblowing Software. Vår chattfunktionalitet inkluderar dedikerade rum för smidig och konfidentiell kommunikation. Den är användarvänlig och bekväm, vilket kräver minimal ansträngning för att starta en konversation. Liksom andra digitala kanaler bibehåller onlinechatten visselblåsarens anonymitet, skyddar deras identitet och säkerställer deras säkerhet. Den tillhandahåller också transkriptioner av konversationer, vilket skapar en tydlig och dokumenterad rapport. Inkludera ett dedikerat chattrum för varje rapporteringskanal.",
        ),
    ),
    /**
     * manager
     */
    'manager' => array(
        // English
        'en_US' => array(
            'label' => "Account as Manager",
            'desc'  => "Create a user account with the 'Manager' role. Managers are essential for ensuring the seamless operation of Phoenix Whistleblowing Software. They oversee the entire system, managing permissions, pipelines, and archives. You can add as many manager accounts as necessary.",
        ),
        // Euskara
        'eu_ES' => array(
            'label' => "Kudeatzaile gisa Kontua",
            'desc'  => "Sortu erabiltzaile kontu bat 'Kudeatzaile' rola batekin. Kudeatzaileek funtzionamendu lausoa bermatzen dute Phoenix Whistleblowing Software-en. Sistema osoa zaintzen dute, baimenak, kanalak eta artxiboak kudeatuz. Gehitu beharrezko kudeatzaile kontuak.",
        ),
        // Bulgarian
        'bg_BG' => array(
            'label' => "Акаунт като мениджър",
            'desc'  => "Създайте потребителски акаунт с ролята \"Мениджър\". Мениджърите са от съществено значение за гарантиране на безпроблемната работа на Phoenix Whistleblowing Software. Те надзирават цялата система, управляват разрешенията, каналите и архивите. Можете да добавите толкова мениджърски акаунти, колкото са необходими.",
        ),
        // Croatian
        'hr' => array(
            'label' => "Račun kao menadžer",
            'desc'  => "Kreirajte korisnički račun s ulogom 'Menadžer'. Menadžeri su ključni za osiguravanje besprijekornog rada Phoenix Whistleblowing Software. Nadziru cijeli sustav, upravljajući dozvolama, cjevovodima i arhivama. Možete dodati koliko god je potrebno korisničkih računa menadžera.",
        ),
        // Czech
        'cs_CZ' => array(
            'label' => "Účet jako Manažer",
            'desc'  => "Vytvořte uživatelský účet s rolí 'Manažer'. Manažeři jsou klíčoví pro bezproblémový chod software Phoenix Whistleblowing. Dozírají na celý systém, spravují oprávnění, kanály a archivy. Můžete přidat tolik manažerských účtů, kolik je potřeba.",
        ),
        // Danish
        'da_DK' => array(
            'label' => "Konto som leder",
            'desc'  => "Opret en brugerkonto med rollen 'Manager'. Ledere er afgørende for at sikre problemfri drift af Phoenix Whistleblowing Software. De overvåger hele systemet, administrerer tilladelser, pipelines og arkiver. Du kan tilføje så mange managerkonti som nødvendigt.",
        ),
        // Dutch
        'nl_NL' => array(
            'label' => "Account als Manager",
            'desc'  => "Maak een gebruikersaccount aan met de rol van 'Manager'. Managers zijn essentieel voor het naadloos functioneren van Phoenix Whistleblowing Software. Ze houden toezicht op het gehele systeem, beheren machtigingen, pijplijnen en archieven. U kunt zoveel manageraccounts toevoegen als nodig is.",
        ),
        // Estonian
        'et' => array(
            'label' => "Konto kui juht",
            'desc'  => "Loo kasutajakonto rolliga 'Juht'. Juhtimine on oluline Phoenix Whistleblowing tarkvara sujuva toimimise tagamiseks. Juhid jälgivad kogu süsteemi, haldavad lubasid, torujuhtmeid ja arhive. Saate lisada nii palju juhi kontosid kui vaja.",
        ),
        // Finnish
        'fi' => array(
            'label' => "Tili Managerina",
            'desc'  => "Luo käyttäjätili roolilla 'Manager'. Managerit ovat välttämättömiä Phoenix Whistleblowing -ohjelmiston saumattoman toiminnan varmistamiseksi. He valvovat koko järjestelmää, hallinnoivat käyttöoikeuksia, putkistoja ja arkistoja. Voit lisätä niin monta manageritiliä kuin tarpeen.",
        ),
        // French
        'fr_FR' => array(
            'label' => "Compte en tant que Manager",
            'desc'  => "Créez un compte utilisateur avec le rôle de \"Manager\". Les managers sont essentiels pour garantir le bon fonctionnement du logiciel de signalement Phoenix. Ils supervisent l'ensemble du système, gèrent les autorisations, les pipelines et les archives. Vous pouvez ajouter autant de comptes managers que nécessaire.",
        ),
        // German
        'de_DE' => array(
            'label' => "Account als Manager",
            'desc'  => "Erstellen Sie ein Benutzerkonto mit der Rolle des \"Managers\". Manager sind unverzichtbar für einen reibungslosen Betrieb der Phoenix-Whistleblowing-Software. Sie überwachen das gesamte System, verwalten Berechtigungen, Pipelines und Archive. Sie können beliebig viele Managerkonten hinzufügen.",
        ),
        // Greek
        'el' => array(
            'label' => "Λογαριασμός ως Διαχειριστής",
            'desc'  => "Δημιουργήστε ένα λογαριασμό χρήστη με τον ρόλο του \"Διαχειριστή\". Οι διαχειριστές είναι απαραίτητοι για την ασφαλή λειτουργία του Phoenix Whistleblowing Software. Εποπτεύουν ολόκληρο το σύστημα, διαχειρίζονται άδειες, αγωγούς και αρχεία. Μπορείτε να προσθέσετε τόσους λογαριασμούς διαχειριστή όσο χρειάζεται.",
        ),
        // Hungarian
        'hu_HU' => array(
            'label' => "Fiók vezetőként",
            'desc'  => "Hozzon létre egy felhasználói fiókot a 'Manager' szerepkörrel. A vezetők nélkülözhetetlenek a Phoenix Whistleblowing szoftver zavartalan működésének biztosításához. Felügyelik az egész rendszert, kezelik az engedélyeket, a csatornákat és az archívumokat. Tetszőleges számú vezetői fiókot hozzáadhat, amennyire szükség van.",
        ),
        // Irish
        'ga_IE' => array(
            'label' => "Cuntas mar Bainisteoir",
            'desc'  => "Cruthaigh cuntas úsáideora le ról an 'Bainisteoir' a bhaint amach. Tá bainisteoirí riachtanacha chun oibriú líonta na Bogearraí Fuascailt Fénix ​​a chinntiú. Féachann siad ar an gcóras iomlán, ag bainistiú ceadanna, píolónna agus cartlanna. Is féidir leat chomh maith is mian leat cuntas bainisteora a chur leis.",
        ),
        // Italian
        'it_IT' => array(
            'label' => "Conto come Manager",
            'desc'  => "Crea un account utente con il ruolo di \"Manager\". I manager sono essenziali per garantire il funzionamento senza problemi del software di segnalazione Phoenix. Sovrano l'intero sistema, gestendo autorizzazioni, pipeline e archivi. È possibile aggiungere quanti account di manager sono necessari.",
        ),
        // Latvian
        'lv_LV' => array(
            'label' => "Konts kā vadītājs",
            'desc'  => "Izveidojiet lietotāja kontu ar \"Vadītājs\" lomu. Vadītāji ir būtiski, lai nodrošinātu \"Phoenix Whistleblowing Software\" nesatrauktas darbības. Viņi uzrauga visu sistēmu, pārvalda atļaujas, cauruļvadus un arhīvus. Jūs varat pievienot tik daudz vadītāju kontus, cik nepieciešams.",
        ),
        // Lithuanian
        'lt_LT' => array(
            'label' => "Paskyra kaip Vadovas",
            'desc'  => "Sukurkite naudotojo paskyrą su \"Vadovo\" vaidmeniu. Vadovai yra būtini, kad užtikrintų sklandų \"Phoenix Whistleblowing Software\" veikimą. Jie prižiūri visą sistemą, valdo leidimus, kanalus ir archyvus. Galite pridėti tiek vadovo paskyrų, kiek reikia.",
        ),
        // Maltese
        'mt_MT' => array(
            'label' => "Konto bħala Maniġer",
            'desc'  => "Oħloq kont ta' utent b'roċċa 'Maniġer'. Il-Maniġers huma essenzjali biex jiżguraw l-operazzjoni bla smigħ tal-Software ta' Phoenix Whistleblowing. Jirregolaw is-sistema kollha, jiġu maniġati d-drittijiet, il-pipelajiet u l-arxivi. Tista' żżid kemm kontijiet tal-maniġer kif meħtieġ.",
        ),
        // Norwegian Nynorsk
        'nb_NO' => array(
            'label' => "Konto som leder",
            'desc'  => "Opprett en brukerkonto med rollen som 'Manager'. Ledere er avgjørende for å sikre den sømløse driften av Phoenix Whistleblowing Software. De overvåker hele systemet, administrerer tillatelser, rørledninger og arkiver. Du kan legge til så mange lederkontoer som nødvendig.",
        ),
        // Polish
        'pl_PL' => array(
            'label' => "Konto jako Menedżer",
            'desc'  => "Utwórz konto użytkownika z rolą „Menedżera”. Menedżerowie są niezbędni do zapewnienia płynnego funkcjonowania oprogramowania Phoenix Whistleblowing. Nadzorują cały system, zarządzając uprawnieniami, kanałami i archiwami. Możesz dodać tyle kont menedżerów, ile jest to konieczne.",
        ),
        // Portuguese
        'pt_PT' => array(
            'label' => "Conta como Gerente",
            'desc'  => "Crie uma conta de usuário com a função de 'Gerente'. Os gerentes são essenciais para garantir o funcionamento contínuo do Software de Denúncia Phoenix. Eles supervisionam todo o sistema, gerenciando permissões, pipelines e arquivos. Você pode adicionar quantas contas de gerente forem necessárias.",
        ),
        // Romanian
        'ro_RO' => array(
            'label' => "Cont ca Manager",
            'desc'  => "Creați un cont de utilizator cu rolul de 'Manager'. Managerii sunt esențiali pentru asigurarea funcționării fără probleme a Software-ului de denunțare Phoenix. Ei supraveghează întregul sistem, gestionând permisiunile, conductele și arhivele. Puteți adăuga câte conturi de manager sunt necesare.",
        ),
        // Slovak
        'sk_SK' => array(
            'label' => "Účet ako Správca",
            'desc'  => "Vytvorte používateľský účet so „Správcom“ rolu. Správcovia sú nevyhnutní pre zabezpečenie bezproblémového fungovania softvéru na odhaľovanie korupcie Phoenix. Dozerajú na celý systém, spravujú povolenia, potrubia a archívy. Môžete pridať toľko správcovských účtov, koľko je potrebných.",
        ),
        // Slovenian
        'sl_SI' => array(
            'label' => "Račun kot Upravitelj",
            'desc'  => "Ustvarite uporabniški račun z vlogo 'Upravitelj'. Upravitelji so ključni za zagotavljanje nemotenega delovanja programske opreme Phoenix Whistleblowing. Nadzirajo celoten sistem, upravljajo dovoljenja, poti in arhive. Dodate lahko toliko uporabniških računov upraviteljev, kolikor je potrebno.",
        ),
        // Spanish
        'es_ES' => array(
            'label' => "Cuenta como Gerente",
            'desc'  => "Cree una cuenta de usuario con el rol de \"Gerente\". Los gerentes son esenciales para garantizar el funcionamiento sin problemas del software de denuncia de irregularidades de Phoenix. Supervisan todo el sistema, gestionando permisos, canalizaciones y archivos. Puede agregar tantas cuentas de gerente como sea necesario.",
        ),
        // Swedish
        'sv_SE' => array(
            'label' => "Konto som Chef",
            'desc'  => "Skapa ett användarkonto med rollen 'Manager'. Chefer är avgörande för att säkerställa den smidiga driften av Phoenix Whistleblowing Software. De övervakar hela systemet, hanterar behörigheter, pipeliner och arkiv. Du kan lägga till så många chefskonton som behövs.",
            ),
        ),
    /**
    * operator
    */
    'operator' => array(
        // English
        'en_US' => array(
            'label' => "Account as Operator",
            'desc'  => "Create a user account with the 'Operator' role. Operators play a critical role in the secure submission of reports, maintaining confidentiality and streamlined access. Their responsibilities include securely managing submissions within defined parameters, ensuring the integrity of the process. You can add as many Operator accounts as necessary.",
        ),
        // Euskara
        'eu_ES' => array(
            'label' => "Kontua Operadore gisa",
            'desc'  => "Sortu erabiltzaile kontu bat 'Operadore' rola batekin. Operadoreek oso funtsezko papera jokatzen dute txostenak seguruan aurkeztean, konfidentzialtasuna eta atzipen zuzena mantentzen. Euren ardura barruan finkatutako parametroetan txostenak seguru kudeatzea, prozesuaren integritatea ziurtatuz. Gehitu beharrezko Operadore kontuak.",
        ),
        // Bulgarian
        'bg_BG' => array(
            'label' => "Акаунт като оператор",
            'desc'  => "Създайте потребителски акаунт с ролята \"Оператор\". Операторите играят критична роля в осигуряването на сигурното подаване на доклади, поддържайки поверителност и оптимизиран достъп. Техните отговорности включват сигурното управление на подадените доклади в рамките на дефинирани параметри, гарантирайки интегритета на процеса. Можете да добавите толкова Операторски акаунти, колкото са необходими.",
        ),
        // Croatian
        'hr' => array(
            'label' => "Račun kao operator",
            'desc'  => "Stvorite korisnički račun s ulogom 'Operator'. Operatori igraju ključnu ulogu u sigurnom podnošenju izvještaja, održavajući povjerljivost i olakšan pristup. Njihove odgovornosti uključuju sigurno upravljanje prijavama unutar definiranih parametara, osiguravajući integritet procesa. Možete dodati koliko god je potrebno korisničkih računa operatora.",
        ),
        // Czech
        'cs_CZ' => array(
            'label' => "Účet jako operátor",
            'desc'  => "Vytvořte uživatelský účet s rolí 'Operátor'. Operátoři hrají klíčovou roli při bezpečném podání hlášení, zachování důvěrnosti a zjednodušeném přístupu. Jejich odpovědnosti zahrnují bezpečné řízení podání v rámci definovaných parametrů, což zajišťuje integritu procesu. Můžete přidat tolik účtů Operátora, kolik je potřeba.",
        ),
        // Danish
        'da_DK' => array(
            'label' => "Konto som operatør",
            'desc'  => "Opret en brugerkonto med rollen 'Operator'. Operatører spiller en afgørende rolle i sikker indsendelse af rapporter, opretholdelse af fortrolighed og strømlinet adgang. Deres ansvar omfatter sikker håndtering af indsendelser inden for definerede parametre, sikring af processens integritet. Du kan tilføje så mange operatørkonti som nødvendigt.",
        ),
        // Dutch
        'nl_NL' => array(
            'label' => "Account als Operator",
            'desc'  => "Maak een gebruikersaccount aan met de rol van 'Operator'. Operators spelen een cruciale rol in de veilige indiening van rapporten, het handhaven van vertrouwelijkheid en gestroomlijnde toegang. Hun verantwoordelijkheden omvatten het veilig beheren van indieningen binnen gedefinieerde parameters, en het waarborgen van de integriteit van het proces. U kunt zoveel Operator-accounts toevoegen als nodig is.",
        ),
        // Estonian
        'et' => array(
            'label' => "Konto operaatorina",
            'desc'  => "Loo kasutajakonto rolliga 'Operaator'. Operaatorid mängivad olulist rolli aruannete turvalises esitamises, säilitades konfidentsiaalsuse ja sujuva juurdepääsu. Nende vastutus hõlmab turvalist haldamist määratletud parameetrite piires, tagades protsessi terviklikkuse. Saate lisada nii palju operaatori kontosid kui vaja.",
        ),
        // Finnish
        'fi' => array(
            'label' => "Tili Operaattorina",
            'desc'  => "Luo käyttäjätili roolilla 'Operaattori'. Operaattorit ovat keskeisessä roolissa raporttien turvallisessa lähettämisessä, ylläpitäen luottamuksellisuutta ja sujuvaa pääsyä. Heidän vastuullaan on turvallinen hallinta määriteltyjen parametrien puitteissa, varmistaen prosessin eheyden. Voit lisätä niin monta operaattoritiliä kuin tarpeen.",
        ),
        // French
        'fr_FR' => array(
            'label' => "Compte en tant qu'Opérateur",
            'desc'  => "Créez un compte utilisateur avec le rôle d''Opérateur'. Les opérateurs jouent un rôle critique dans la soumission sécurisée des rapports, en maintenant la confidentialité et l'accès simplifié. Leurs responsabilités incluent la gestion sécurisée des soumissions dans les paramètres définis, garantissant l'intégrité du processus. Vous pouvez ajouter autant de comptes Opérateurs que nécessaire.",
        ),
        // German
        'de_DE' => array(
            'label' => "Konto als Operator",
            'desc'  => "Erstellen Sie ein Benutzerkonto mit der Rolle des 'Operators'. Operatoren spielen eine entscheidende Rolle bei der sicheren Einreichung von Berichten, wahren die Vertraulichkeit und gewährleisten einen reibungslosen Zugang. Zu ihren Aufgaben gehört die sichere Verwaltung von Einreichungen innerhalb definierter Parameter, um die Integrität des Prozesses zu gewährleisten. Sie können beliebig viele Operator-Konten hinzufügen.",
        ),
        // Greek
        'el' => array(
            'label' => "Λογαριασμός ως Λειτουργός",
            'desc'  => "Δημιουργήστε ένα λογαριασμό χρήστη με τον ρόλο του \"Λειτουργού\". Οι λειτουργοί παίζουν κρίσιμο ρόλο στην ασφαλή υποβολή αναφορών, διατηρώντας την εμπιστευτικότητα και τη ρευστή πρόσβαση. Οι ευθύνες τους περιλαμβάνουν την ασφαλή διαχείριση υποβολών εντός ορισμένων παραμέτρων, εξασφαλίζοντας την ακεραιότητα της διαδικασίας. Μπορείτε να προσθέσετε τόσους λογαριασμούς λειτουργού όσο χρειάζεται.",
        ),
        // Hungarian
        'hu_HU' => array(
            'label' => "Fiók operátorként",
            'desc'  => "Hozzon létre egy felhasználói fiókot az 'Operátor' szerepkörrel. Az operátorok kulcsfontosságú szerepet játszanak a jelentések biztonságos benyújtásában, megőrizve a bizalmas jellegüket és az egyszerűsített hozzáférést. Feladatkörük magában foglalja a beadványok biztonságos kezelését meghatározott paraméterek között, biztosítva a folyamat integritását. Tetszőleges számú operátori fiókot hozzáadhat, amennyire szükség van.",
        ),
        // Irish
        'ga_IE' => array(
            'label' => "Cuntas mar Oibreora",
            'desc'  => "Cruthaigh cuntas úsáideora le ról an 'Oibreora' a bhaint amach. Tá ról criticiúil ag oibritheoirí i dtaca le hiarratais a chur isteach go sábháilte, ag coimeád rúndacht agus rochtain díreach. Tá sé ar a n-aon ghuthanna a bhainistiú go sábháilte laistigh de na paraiméadair a shainmhínítear, ag chinntiú críochdheighilt an phróisis. Is féidir leat chomh maith is mian leat cuntas Oibreora a chur leis.",
        ),
        // Italian
        'it_IT' => array(
            'label' => "Account come Operatore",
            'desc'  => "Crea un account utente con il ruolo di \"Operatore\". Gli operatori svolgono un ruolo critico nella presentazione sicura delle segnalazioni, mantenendo la confidenzialità e l'accesso semplificato. Le loro responsabilità includono la gestione sicura delle segnalazioni entro parametri definiti, garantendo l'integrità del processo. È possibile aggiungere quanti account di operatore sono necessari.",
        ),
        // Latvian
        'lv_LV' => array(
            'label' => "Konts kā operators",
            'desc'  => "Izveidojiet lietotāja kontu ar \"Operators\" lomu. Operatori spēlē kritisku lomu ziņojumu drošā iesniegšanā, nodrošinot konfidencialitāti un vienkāršotu piekļuvi. Viņu pienākumi ietver drošu ziņojumu pārvaldīšanu noteiktos parametros, nodrošinot procesa integritāti. Jūs varat pievienot tik daudz Operatora kontus, cik nepieciešams.",
        ),
        // Lithuanian
        'lt_LT' => array(
            'label' => "Paskyra kaip Operatorius",
            'desc'  => "Sukurkite naudotojo paskyrą su \"Operatoriaus\" vaidmeniu. Operatoriai atlieka kritinį vaidmenį saugiai pranešimų pateikimo srityje, išlaikant konfidencialumą ir supaprastintą prieigą. Jų pareigos apima saugų pranešimų tvarkymą nustatytais parametrais, užtikrinant proceso vientisumą. Galite pridėti tiek Operatoriaus paskyrų, kiek reikia.",
        ),
        // Maltese
        'mt_MT' => array(
            'label' => "Kont bħala Operatur",
            'desc'  => "Oħloq kont ta' utent b'roċċa 'Operatur'. L-Operaturi jilgħbu roċċa kritika fil-ġabra sigura ta' rapporti, li jżommu r-rwol kif hu taħt il-kopertura u l-aċċess. Ir-rwoli tagħhom jinkludu l-ġabra sigura ta' rapporti fi pararimetri definiti, li jissieħbu l-integrità tal-proċess. Tista' żżid kemm kontijiet tal-Operatur kif meħtieġ.",
        ),
        // Norwegian Nynorsk
        'nb_NO' => array(
            'label' => "Konto som operatør",
            'desc'  => "Opprett ein brukarkonto med rolla som 'Operatør'. Operatørar spelar ei avgjerande rolle i trygg innsending av rapportar, bevaring av konfidensialitet og forenkla tilgang. Deira ansvar inkluderer trygg handtering av innsendingar innanfor definerte parameter, og sikrar prosessens integritet. Du kan leggje til så mange operatørkonti som er naudsynt.",
        ),
        // Polish
        'pl_PL' => array(
            'label' => "Konto jako Operator",
            'desc'  => "Utwórz konto użytkownika z rolą „Operatora”. Operatorzy odgrywają kluczową rolę w bezpiecznym przesyłaniu raportów, dbając o zachowanie poufności i ułatwiony dostęp. Ich obowiązki obejmują bezpieczne zarządzanie przesyłkami w określonych parametrach, zapewniając integralność procesu. Możesz dodać tyle kont Operatorów, ile jest to konieczne.",
        ),
        // Portuguese
        'pt_PT' => array(
            'label' => "Conta como Operador",
            'desc'  => "Crie uma conta de utilizador com o papel de 'Operador'. Os operadores desempenham um papel crítico no envio seguro de relatórios, mantendo a confidencialidade e o acesso simplificado. As suas responsabilidades incluem a gestão segura de submissões dentro de parâmetros definidos, garantindo a integridade do processo. Pode adicionar tantas contas de Operador quantas forem necessárias.",
        ),
        // Romanian
        'ro_RO' => array(
            'label' => "Cont ca Operator",
            'desc'  => "Creați un cont de utilizator cu rolul de 'Operator'. Operatorii joacă un rol critic în trimiterea securizată a rapoartelor, menținând confidențialitatea și accesul simplificat. Responsabilitățile lor includ gestionarea securizată a trimiterilor în parametrii definiți, asigurând integritatea procesului. Puteți adăuga câte conturi de Operator sunt necesare.",
        ),
        // Slovak
        'sk_SK' => array(
            'label' => "Účet ako Operátor",
            'desc'  => "Vytvorte používateľský účet s rolou \"Operátor\". Operátori hrajú kľúčovú úlohu pri bezpečnom odosielaní správ, udržiavaní dôvernosti a jednoduchom prístupe. Ich zodpovednosti zahŕňajú bezpečné spravovanie odoslaných správ v rámci definovaných parametrov a zabezpečenie integrity procesu. Môžete pridať toľko operátorských účtov, koľko je potrebných.",
        ),
        // Slovenian
        'sl_SI' => array(
            'label' => "Račun kot Operater",
            'desc'  => "Ustvarite uporabniški račun z vlogo 'Operater'. Operaterji igrajo ključno vlogo pri varnem oddajanju poročil, ohranjanju zaupnosti in",
        ),
        // Spanish
        'es_ES' => array(
            'label' => "Cuenta como Operador",
            'desc'  => "Cree una cuenta de usuario con el rol de 'Operador'. Los operadores desempeñan un papel crítico en la presentación segura de informes, manteniendo la confidencialidad y el acceso simplificado. Sus responsabilidades incluyen la gestión segura de presentaciones dentro de parámetros definidos, asegurando la integridad del proceso. Puede agregar tantas cuentas de Operador como sea necesario.",
        ),
        // Swedish
        'sv_SE' => array(
            'label' => "Konto som Operatör",
            'desc'  => "Skapa ett användarkonto med rollen 'Operator'. Operatörer spelar en avgörande roll för den säkra inlämningen av rapporter, upprätthållande av konfidentialitet och strömlinjeformat åtkomst. Deras ansvar inkluderar säker hantering av inlämningar inom definierade parametrar, vilket säkerställer integriteten i processen. Du kan lägga till så många operatörskonton som behövs.",
            ),
        ),
    /**
    * agent
    */
    'agent' => array(
        // English
        'en_US' => array(
            'label' => "Account as Agent",
            'desc'  => "Create a user account with the 'Agent' designation, granting specialized access privileges. Agents are authorized to view disclosures shared exclusively by managers or operators. Their permissions include adding events, internal notes, and comments alongside whistleblowers. While their role is specific, it plays a crucial part in escalating disclosures to external entities such as compliance departments or investigation firms. You can add as many Agent accounts as necessary.",
        ),
        // Euskara (Basque)
        'eu_ES' => array(
            'label' => "Agerente gisa Kontua",
            'desc'  => "Sortu erabiltzaile kontu bat 'Agerente' izendatzea, baimen espezialak ematen dituena. Agerenteei baimenak eman zaizkie bereziki kudeatzaile edo operadoreek partekatutako abisuak ikusteko. Bere baimenek gertaeren, barne oharrak eta iruzurrak erantsi ahal izango dituzte aholkularien alboan. Bere rolak zehazkiak diren arren, funtzio horrek garrantzitsua da abisuen eskalazioan atalen kanpoko erakundeetara, hala nola biderik gabeko sailak edo ikerketa enpresekuntzak. Gehitu beharrezko Agerente kontuak.",
        ),
        // Bulgarian
        'bg_BG' => array(
            'label' => "Акаунт като агент",
            'desc'  => "Създайте потребителски акаунт с означение \"Агент\", който предоставя специализирани привилегии за достъп. Агентите са упълномощени да преглеждат разкрития, споделени ексклузивно от мениджъри или оператори. Техните разрешения включват добавяне на събития, вътрешни бележки и коментари заедно с уведомителите. Въпреки че тяхната роля е специфична, тя играе критична роля в увеличаването на разкритията до външни организации като отделите за съответствие или фирми за разследвания. Можете да добавите толкова Агентски акаунти, колкото са необходими.",
        ),
        // Croatian
        'hr' => array(
            'label' => "Račun kao agent",
            'desc'  => "Kreirajte korisnički račun s oznakom 'Agent', koja dodjeljuje specijalizirane pristupne ovlasti. Agenti su ovlašteni pregledavati otkrića koja dijele isključivo menadžeri ili operatori. Njihove ovlasti uključuju dodavanje događaja, internih bilješki i komentara uz uzbunjivače. Iako je njihova uloga specifična, igraju ključnu ulogu u eskalaciji otkrića prema vanjskim entitetima poput odjela za usklađenost ili tvrtki za istraživanje. Možete dodati koliko god je potrebno korisničkih računa agenata.",
        ),
        // Czech
        'cs_CZ' => array(
            'label' => "Účet jako Agent",
            'desc'  => "Vytvořte uživatelský účet s označením 'Agent', který poskytuje specializovaná oprávnění k přístupu. Agenti jsou oprávněni zobrazovat hlášení sdílená výhradně manažery nebo operátory. Jejich oprávnění zahrnují přidávání událostí, interních poznámek a komentářů spolu s whistleblowerem. Jejich role je sice specifická, ale hraje klíčovou roli při eskalaci hlášení k externím subjektům, jako jsou oddělení pro soulad nebo vyšetřovací firmy. Můžete přidat tolik účtů Agent, kolik je potřeba.",
        ),
        // Danish
        'da_DK' => array(
            'label' => "Konto som agent",
            'desc'  => "Opret en brugerkonto med betegnelsen 'Agent', som giver specialiserede adgangsrettigheder. Agenter er tilladt at se offentliggørelser, der deles udelukkende af ledere eller operatører. Deres tilladelser omfatter tilføjelse af begivenheder, interne notater og kommentarer ved siden af klokkenlæsere. Selvom deres rolle er specifik, spiller den en afgørende rolle i eskaleringen af offentliggørelser til eksterne enheder som compliance-afdelinger eller undersøgelsesfirmaer. Du kan tilføje så mange Agent-konti, som der er behov for.",
        ),
        // Dutch
        'nl_NL' => array(
            'label' => "Account als Agent",
            'desc'  => "Maak een gebruikersaccount aan met de aanduiding 'Agent', waarmee gespecialiseerde toegangsprivileges worden verleend. Agents zijn gemachtigd om alleen door managers of operators gedeelde openbaarmakingen te bekijken. Hun bevoegdheden omvatten het toevoegen van gebeurtenissen, interne notities en opmerkingen naast klokkenluiders. Hoewel hun rol specifiek is, speelt deze een cruciale rol bij het escaleren van openbaarmakingen naar externe entiteiten zoals compliance-afdelingen of onderzoeksbureaus. U kunt zoveel Agent-accounts toevoegen als nodig is.",
        ),
        // Estonian
        'et' => array(
            'label' => "Konto kui agent",
            'desc'  => "Loo kasutajakonto märgisega 'Agent', andes spetsialiseeritud juurdepääsuõigused. Agendid on volitatud vaatama ainult juhtide või operaatoritega jagatud avaldusi. Nende õigused hõlmavad sündmuste lisamist, sisemiste märkmete ja kommentaaride lisamist kõrvuti teatavate isikutega. Kuigi nende roll on spetsiifiline, mängib see olulist osa avalduste eskalatsioonis välisorganisatsioonidele nagu vastavusosakonnad või uurimisfirmad. Saate lisada nii palju agentide kontosid, kui on vajalik.",
        ),
        // Finnish
        'fi' => array(
            'label' => "Tili Agenttina",
            'desc'  => "Luo käyttäjätili 'Agent' -nimikkeellä, joka myöntää erikoistuneita pääsyoikeuksia. Agentit ovat valtuutettuja näkemään yksinomaan managerien tai operaattoreiden jakamia paljastuksia. Heidän käyttöoikeutensa sisältävät tapahtumien, sisäisten muistiinpanojen ja kommenttien lisäämisen kellokkaiden rinnalla. Vaikka heidän roolinsa on tarkka, se on keskeinen osa paljastusten eskalointia ulkoisille tahoille, kuten noudatustarkastusosastoille tai tutkimustoimistoille. Voit lisätä niin monta agenttitiliä kuin tarpeen.",
        ),
        // French
        'fr_FR' => array(
            'label' => "Compte en tant qu'agent",
            'desc'  => "Créez un compte utilisateur avec la désignation \"Agent\", octroyant des privilèges d'accès spécialisés. Les agents sont autorisés à consulter les divulgations partagées exclusivement par les gestionnaires ou les opérateurs. Leurs permissions incluent l'ajout d'événements, de notes internes et de commentaires auprès des lanceurs d'alerte. Bien que leur rôle soit spécifique, il joue un rôle crucial dans l'escalade des divulgations vers des entités externes telles que les départements de conformité ou les firmes d'investigation. Vous pouvez ajouter autant de comptes d'agents que nécessaire.",
        ),
        // German
        'de_DE' => array(
            'label' => "Konto als Agent",
            'desc'  => "Erstellen Sie ein Benutzerkonto mit der Bezeichnung \"Agent\", das spezialisierte Zugriffsrechte gewährt. Agenten dürfen ausschließlich von Managern oder Betreibern geteilte Offenlegungen einsehen. Ihre Berechtigungen umfassen das Hinzufügen von Ereignissen, internen Notizen und Kommentaren neben Hinweisgebern. Obwohl ihre Rolle spezifisch ist, spielt sie eine entscheidende Rolle bei der Eskalation von Offenlegungen an externe Einheiten wie Compliance-Abteilungen oder Ermittlungsfirmen. Sie können beliebig viele Agentenkonten hinzufügen.",
        ),
        // Greek
        'el' => array(
            'label' => "Λογαριασμός ως Πράκτορας",
            'desc'  => "Δημιουργήστε έναν λογαριασμό χρήστη με την επωνυμία \"Πράκτορας\", παρέχοντας εξειδικευμένα δικαιώματα πρόσβασης. Οι πράκτορες επιτρέπεται να δουν αποκλειστικά αποκαλύψεις που έχουν μοιραστεί από διευθυντές ή χειριστές. Οι άδειές τους περιλαμβάνουν την προσθήκη συμβάντων, εσωτερικών σημειώσεων και σχολίων δίπλα στους καταγγέλτες. Παρόλο που ο ρόλος τους είναι συγκεκριμένος, παίζουν κρίσιμο ρόλο στην ενίσχυση των αποκαλύψεων προς εξωτερικούς φορείς, όπως οι τμήματα συμμόρφωσης ή ερευνητικές εταιρείες. Μπορείτε να προσθέσετε όσους λογαριασμούς πρακτόρων χρειάζεστε.",
        ),
        // Hungarian
        'hu_HU' => array(
            'label' => "Fiók mint Ügynök",
            'desc'  => "Hozzon létre egy felhasználói fiókot \"Ügynök\" meghatározással, amely speciális hozzáférési jogosultságokat biztosít. Az ügynökök csak vezetők vagy üzemeltetők által megosztott felfedéseket tekinthetik meg. Jogosultságaik magukban foglalják események, belső jegyzetek és megjegyzések hozzáadását a besúgók mellett. Bár szerepük specifikus, fontos szerepet játszanak a felfedések továbbításában külső szervezetek felé, például a szabályozási osztályok vagy a nyomozó cégek felé. Tetszőleges számú ügynökfiókot adhat hozzá.",
        ),
        // Irish
        'ga_IE' => array(
            'label' => "Cuntas mar Aisíneoir",
            'desc'  => "Cruthaigh cuntas úsáideora le hainmniú \"Aisíneoir\", ag tabhairt sainchearta rochtana. Tá cead ag na haisíneoirí fianaise a fheiceáil a roinntear ag bainisteoirí nó oibríonn. Ar a gcumas tá freagrachtaí iontrála a chur le haghaidh imeachtaí, nótaí inmheánacha agus tráchtaí taobh le hionchuraithe. Cé go bhfuil ról sonrach acu, tá ról tábhachtach acu i scálú na n-aisíntí chuig eagraíochtaí seachtracha cosúil le ranna comhlíonta nó cuideachtaí imscrúdaithe. Is féidir leat aon uimhir cuntasacha aisíneoir a chur leis.",
        ),
        // Italian
        'it_IT' => array(
            'label' => "Account come Agente",
            'desc'  => "Crea un account utente con la designazione \"Agente\", conferendo privilegi di accesso specializzati. Gli agenti sono autorizzati a visualizzare solo le segnalazioni condivise esclusivamente da manager o operatori. Le loro autorizzazioni includono l'aggiunta di eventi, note interne e commenti accanto ai whistleblower. Nonostante il loro ruolo specifico, svolgono un ruolo cruciale nell'escalation delle segnalazioni verso entità esterne come i reparti di compliance o le società investigative. È possibile aggiungere qualsiasi numero di account agenti.",
        ),
        // Latvian
        'lv_LV' => array(
            'label' => "Konta kā aģents",
            'desc'  => "Izveidojiet lietotāja kontu ar nosaukumu \"Aģents\", piešķirot specializētas piekļuves tiesības. Aģenti ir pilnvaroti skatīt tikai vadītāju vai operatoru kopīgotās atklāsmes. Viņu atļaujas ietver notikumu, iekšējo piezīmju un komentāru pievienošanu blakus iesūtītājiem. Lai gan viņu loma ir specifiska, tā ir būtiska loma atklāsmju eskalācijā uz ārējām entitātēm, piemēram, atbilstības nodaļām vai izmeklēšanas uzņēmumiem. Jūs varat pievienot tik daudz aģentu kontus, cik nepieciešams.",
        ),
        // Lithuanian
        'lt_LT' => array(
            'label' => "Paskyra kaip Agentas",
            'desc'  => "Sukurkite vartotojo paskyrą su pavadinimu \"Agentas\", suteikiančią specializuotas prieigos teises. Agentai gali peržiūrėti tik vadovų arba operatorių bendrinamus atskleidimus. Jų leidimai apima įvykių, vidaus pastabų ir komentarų pridėjimą kartu su pranešėjais. Nepaisant jų konkretaus vaidmens, jie atlieka lemiamą vaidmenį atskleidimų eskalavime į išorines subjektus, pvz., atitikties skyrius ar tyrimo įmones. Galite pridėti tiek agentų paskyrų, kiek reikia.",
        ),
        // Maltese
        'mt_MT' => array(
            'label' => "Kont għall-Aġent",
            'desc'  => "Oħloq kont tal-utent b'identi \"Aġent\", li jipprovdi privileġġi ta' aċċess speċjalizzati. L-aġenti huma awtorizzati biex jaraw biss rivelazzjonijiet mħajjra minn maniġers jew operators. Il-permessi tagħhom jinkludu żieda ta' avvenimenti, notaġġi interni u kommenti flimkien ma' talbaħjar ta' lanċjar. Għalkemm ir-ruħ tagħhom huwa speċifiku, huma joħorġu rolu kruċjali fl-iskalament ta' rivelazzjonijiet lejn entitajiet esterni bħall-unitajiet ta' konformità jew kumpaniji ta' investiġazzjoni. Tista' żżid numru kwalunkwe ta' kontijiet ta' aġenti.",
        ),
        // Norwegian Nynorsk
        'nb_NO' => array(
            'label' => "Konto som Agent",
            'desc'  => "Opprett ein brukarkonto med nemninga \"Agent\", som gir spesialiserte tilgangsrettar. Agenter har løyve til å sjå berre avsløringar som er delt av leiarar eller operatørar. Deira løyver inkluderer å leggje til hendingar, interne merknader og kommentarar ved sidan av varslarar. Sjølv om rolla deira er spesifikk, spelar dei ei viktig rolle i eskaleringa av avsløringar til eksterne enhetar som samsvarsavdelingar eller etterforskingsselskap. Du kan leggje til så mange agentkontoar som du treng.",
        ),
        // Polish
        'pl_PL' => array(
            'label' => "Konto jako Agent",
            'desc'  => "Utwórz konto użytkownika o nazwie \"Agent\", nadające specjalistyczne uprawnienia dostępu. Agenci mogą przeglądać tylko wyjawione informacje udostępnione przez menedżerów lub operatorów. Ich uprawnienia obejmują dodawanie zdarzeń, wewnętrznych notatek i komentarzy obok donosicieli. Pomimo specyficznej roli, odgrywają kluczową rolę w eskalacji wyjawiania do zewnętrznych podmiotów, takich jak działy zgodności czy firmy śledcze. Możesz dodawać tyle kont agentów, ile potrzebujesz.",
        ),
        // Portuguese
        'pt_PT' => array(
            'label' => "Conta como Agente",
            'desc'  => "Crie uma conta de utilizador com a designação \"Agente\", conferindo privilégios de acesso especializados. Os agentes têm permissão para visualizar apenas divulgações partilhadas exclusivamente por gestores ou operadores. As suas permissões incluem adicionar eventos, notas internas e comentários ao lado dos denunciantes. Apesar do seu papel específico, desempenham um papel crucial na escalada de divulgações para entidades externas como departamentos de conformidade ou empresas de investigação. Pode adicionar tantas contas de agentes quantas forem necessárias.",
        ),
        // Romanian
        'ro_RO' => array(
            'label' => "Cont ca Agent",
            'desc'  => "Creați un cont de utilizator cu denumirea \"Agent\", conferind privilegii specializate de acces. Agenții au permisiunea de a vizualiza doar dezvăluiri distribuite exclusiv de manageri sau operatori. Permisiunile lor includ adăugarea de evenimente, note interne și comentarii alături de denunțători. În ciuda rolului lor specific, aceștia joacă un rol crucial în escaladarea dezvăluirilor către entități externe precum departamentele de conformitate sau firmele de investigații. Puteți adăuga câte conturi de agenți doriți.",
        ),
        // Slovak
        'sk_SK' => array(
            'label' => "Účet ako Agent",
            'desc'  => "Vytvorte používateľský účet s označením \"Agent\", ktorý poskytuje špecializované prístupové práva. Agenti majú povolenie prezerávať len zdieľané odhalenia výlučne manažérmi alebo operátormi. Ich oprávnenia zahŕňajú pridávanie udalostí, interných poznámok a komentárov vedľa upozornení. Napriek ich špecifickému úlohu zohrávajú kľúčovú úlohu pri eskalácii odhalení k externým subjektom, ako sú oddelenia zhody alebo vyšetrovacie spoločnosti. Môžete pridať ľubovoľný počet účtov agentov.",
        ),
        // Slovenian
        'sl_SI' => array(
            'label' => "Račun kot Agent",
            'desc'  => "Ustvarite uporabniški račun z oznako \"Agent\", ki omogoča specializirane dostopne pravice. Agenti imajo dovoljenje za ogled samo razkritij, ki so jih delili izključno upravljavci ali operaterji. Njihova dovoljenja vključujejo dodajanje dogodkov, notranjih opomb in komentarjev ob varuhu. Čeprav je njihova vloga specifična, imajo ključno vlogo pri eskalaciji razkritij k zunanjim subjektom, kot so oddelki za skladnost ali preiskovalna podjetja. Lahko dodate poljubno število računov agentov.",
        ),
        // Spanish
        'es_ES' => array(
            'label' => "Cuenta como Agente",
            'desc'  => "Cree una cuenta de usuario con la designación \"Agente\", otorgando privilegios de acceso especializados. Los agentes tienen permiso para ver solo divulgaciones compartidas exclusivamente por gerentes u operadores. Sus permisos incluyen agregar eventos, notas internas y comentarios junto a los denunciantes. A pesar de su rol específico, juegan un papel crucial en la escalada de divulgaciones hacia entidades externas como departamentos de cumplimiento o firmas de investigación. Puede agregar tantas cuentas de agentes como sea necesario.",
        ),
        // Swedish
        'sv_SE' => array(
            'label' => "Konto som Agent",
            'desc'  => "Skapa ett användarkonto med beteckningen \"Agent\", vilket ger specialiserade åtkomsträttigheter. Agenter har behörighet att endast se avslöjanden som delats av chefer eller operatörer. Deras behörigheter inkluderar att lägga till händelser, interna anteckningar och kommentarer bredvid varnare. Trots deras specifika roll spelar de en avgörande roll i eskaleringen av avslöjanden till externa enheter som efterlevnadsavdelningar eller utredningsföretag. Du kan lägga till så många agentkonton som behövs.",
        ),
    ),
);