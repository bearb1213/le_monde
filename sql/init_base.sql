
CREATE TABLE IF NOT EXISTS articles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(255),
    html TEXT
) ;

CREATE INDEX idx_reference ON articles(id);
CREATE INDEX idx_titre ON articles(titre);


CREATE TABLE IF NOT EXISTS article_images (
    id INT AUTO_INCREMENT PRIMARY KEY,
    article_id INT,
    chemin VARCHAR(255),
    alt TEXT,
    CONSTRAINT fk_article_images_article FOREIGN KEY (article_id) REFERENCES articles(id)
) ;

CREATE TABLE IF NOT EXISTS article_details (
    id INT AUTO_INCREMENT PRIMARY KEY,
    article_id INT,
    details INT,
    CONSTRAINT fk_article_details_article FOREIGN KEY (article_id) REFERENCES articles(id),
    CONSTRAINT fk_article_details_details FOREIGN KEY (details) REFERENCES articles(id)
) ;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) UNIQUE,
    password VARCHAR(255)
);

INSERT INTO users (username, password) VALUES ('admin', 'admin') , ('Jean Jacques', 'jj123') , ('Marie Curie', 'mc456') , ('Albert Henry', 'ae789');

ALTER TABLE articles
ADD COLUMN date_publication TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
ADD COLUMN auteur INTEGER;

ALTER TABLE articles
ADD CONSTRAINT fk_article_auteur FOREIGN KEY (auteur) REFERENCES users(id);

INSERT INTO articles (titre,html,date_publication,auteur) VALUES 
('EN DIRECT, guerre au Moyen-Orient  deux porte-conteneurs chinois ont traverse le detroit d Ormuz lundi  le electricite retablie dans la plupart des quartiers de Teheran', '<h1 id="js-title-live" class="title__sirius-live">EN DIRECT, guerre au Moyen-Orient : deux porte-conteneurs chinois ont travers&eacute; le d&eacute;troit d&rsquo;Ormuz lundi ; l&rsquo;&eacute;lectricit&eacute; r&eacute;tablie dans la plupart des quartiers de T&eacute;h&eacute;ran</h1>
<h2>Des explosions et des coupures de courant avaient &eacute;t&eacute; signal&eacute;es &agrave; T&eacute;h&eacute;ran par des m&eacute;dias iraniens. L&rsquo;&eacute;lectricit&eacute; a &eacute;t&eacute; r&eacute;tablie pour la plupart des clients touch&eacute;s, mardi matin, a annonc&eacute; le directeur g&eacute;n&eacute;ral de la compagnie de distribution d&rsquo;&eacute;lectricit&eacute;.</h2>
<p><img src="/image/img_69cb821dacf618.21552668.jpeg" alt="EN DIRECT, guerre au Moyen-Orient : deux porte-conteneurs chinois ont travers&eacute; le d&eacute;troit d&rsquo;Ormuz lundi ; l&rsquo;&eacute;lectricit&eacute; r&eacute;tablie dans la plupart des quartiers de T&eacute;h&eacute;ran" width="1600" height="800"></p>      
<h2 class="post__live-container--title post__space-node">Deux hommes reconnus coupables d&rsquo;appartenance aux Moudjahidin du peuple (MeK) ex&eacute;cut&eacute;s en Iran</h2>
<p>Deux hommes reconnus coupables d&rsquo;appartenance &agrave; un groupe d&rsquo;opposition interdit et de conspiration visant &agrave; renverser la R&eacute;publique islamique ont &eacute;t&eacute; ex&eacute;cut&eacute;s mardi en Iran, a annonc&eacute; le pouvoir judiciaire.&nbsp;<em>&laquo;&nbsp;Babak Alipour et Pouya Ghobadi ont &eacute;t&eacute; ex&eacute;cut&eacute;s par pendaison &agrave; l&rsquo;issue de proc&eacute;dures judiciaires et apr&egrave;s confirmation de leurs peines par la Cour supr&ecirc;me&nbsp;&raquo;</em>, selon le site Internet&nbsp;<em>Mizan Online</em>, organe du pouvoir judiciaire iranien.</p>
<p>Les deux hommes ont &eacute;t&eacute; reconnus coupables de&nbsp;<em>&laquo;&nbsp;participation &agrave; de nombreux actes terroristes&nbsp;&raquo;</em>, d&rsquo;appartenance aux Moudjahidin du peuple (MeK, pour<em>&nbsp;Moudjahidin-e Khalq</em>), organisation en exil depuis les ann&eacute;es 1980, et d&rsquo;actes de sabotage visant au renversement de la R&eacute;publique islamique.</p>
<p>Lundi, le pouvoir judiciaire avait d&eacute;j&agrave; annonc&eacute; l&rsquo;ex&eacute;cution de deux hommes, &eacute;galement membres des MeK, coupables d&rsquo;avoir men&eacute; des actions visant &agrave; renverser la R&eacute;publique islamique et d&rsquo;avoir port&eacute; atteinte &agrave; la s&eacute;curit&eacute; nationale.</p>
<hr>
<h3>08:32</h3>
<h4 class="post__live-container--title post__space-node">L&rsquo;arm&eacute;e iranienne annonce avoir abattu un drone am&eacute;ricain MQ9</h4>
<p>La d&eacute;fense a&eacute;rienne iranienne a annonc&eacute; mardi dans une d&eacute;claration relay&eacute;e par l&rsquo;agence de presse Fars avoir&nbsp;<em>&laquo;&nbsp;intercept&eacute; et abattu avec succ&egrave;s&nbsp;&raquo;</em>&nbsp;un drone am&eacute;ricain MQ9&nbsp;pr&egrave;s d&rsquo;Ispahan et l&rsquo;avoir&nbsp;<em>&laquo;&nbsp;d&eacute;truit&nbsp;&raquo;.&nbsp;</em>Selon l&rsquo;arm&eacute;e, cela porte &agrave; 146 le nombres de drones d&eacute;truits par leur d&eacute;fense a&eacute;rienne.</p>
<hr>
<h3>08:17</h3>
<h4 class="post__live-container--title post__space-node">Deux porte-conteneurs chinois ont travers&eacute; le d&eacute;troit d&rsquo;Ormuz lundi</h4>
<p>Selon les donn&eacute;es du site de suivi des navires Marine Traffic, le porte-conteneurs CSCL&nbsp;<em>Indian-Ocean</em>, appartenant au grand groupe chinois de transport maritime Cosco, a franchi avec succ&egrave;s le d&eacute;troit d&rsquo;Ormuz lundi &agrave; 11&nbsp;h 14&nbsp;(heure &agrave; Paris), suivi du CSCL&nbsp;<em>Arctic-Ocean</em> vingt-sept&nbsp;minutes plus tard.</p>
<hr>
<h3>08:15</h3>
<h4 class="post__live-container--title post__space-node">Une r&eacute;union d&rsquo;urgence du Conseil de s&eacute;curit&eacute; de l&rsquo;ONU pr&eacute;vue mardi</h4>
<div class="post__live-container--answer">
<p class="post__live-container--answer-text post__space-node">Le Conseil de s&eacute;curit&eacute; des Nations unies (ONU) doit se r&eacute;unir en urgence mardi apr&egrave;s le d&eacute;c&egrave;s de trois casques bleus indon&eacute;siens dans le sud du Liban, o&ugrave; Isra&euml;l a annonc&eacute; mardi la mort de quatre de ses soldats. Cela porte &agrave; 10&nbsp;le nombre de soldats isra&eacute;liens morts depuis la reprise des hostilit&eacute;s avec le mouvement pro-iranien Hezbollah le 2&nbsp;mars.</p>
</div>
<div class="post__live-container--answer">
<p class="post__live-container--answer-text post__space-node">L&rsquo;arm&eacute;e isra&eacute;lienne, qui m&egrave;ne au Liban des frappes massives et, dans le sud du pays, une profonde incursion terrestre, a pr&eacute;cis&eacute; avoir identifi&eacute; trois soldats d&rsquo;une brigade de reconnaissance morts <em>&laquo;&nbsp;au combat&nbsp;&raquo;</em> lundi et ajout&eacute; qu&rsquo;un quatri&egrave;me avait &eacute;t&eacute; tu&eacute;, sans transmettre plus d&rsquo;&eacute;l&eacute;ments le concernant.</p>
</div>
<div class="post__live-container--answer">
<p class="post__live-container--answer-text post__space-node">La r&eacute;union de l&rsquo;ONU, qui d&eacute;butera &agrave; 16&nbsp;heures (heure &agrave; Paris), a &eacute;t&eacute; demand&eacute;e par la France apr&egrave;s le d&eacute;c&egrave;s de trois casques bleus indon&eacute;siens dans le sud du Liban. La Force int&eacute;rimaire des Nations unies au Liban (Finul) a dit enqu&ecirc;ter apr&egrave;s que deux soldats indon&eacute;siens ont &eacute;t&eacute; tu&eacute;s <em>&laquo;&nbsp;par une explosion d&rsquo;origine inconnue ayant d&eacute;truit leur v&eacute;hicule pr&egrave;s de Bani Hayyan&nbsp;&raquo;</em> &agrave; la fronti&egrave;re. Deux autres ont &eacute;t&eacute; bless&eacute;s, selon son communiqu&eacute; publi&eacute; lundi.</p>   
<hr>
<p class="post__live-container--answer-text post__space-node">&nbsp;</p>
</div>', '2026-03-03 10:00:00', 2) , 

('Guerre en Iran  ce qu il faut retenir d un mois de conflit' , '<h1>Guerre en Iran&nbsp;: ce qu&rsquo;il faut retenir d&rsquo;un mois de conflit</h1>
<div class="ds-description"><span class="ds-chapo">Les frappes isra&eacute;lo-am&eacute;ricaines et la riposte de l&rsquo;Iran ont plong&eacute; le Moyen-Orient dans une crise aux r&eacute;percussions militaires, diplomatiques et &eacute;conomiques multiples. Les D&eacute;codeurs font le point sur la situation.</span></div>  
<div class="ds-description">&nbsp;</div>
<div class="ds-description"><span class="ds-chapo"><img src="/image/img_69cb887538f4e6.59476169.jpeg" alt="Des missiles iraniens &agrave; T&eacute;h&eacute;ran, le 26&nbsp;mars&nbsp;2026.&nbsp;MAJID ASGARIPOUR/WANA VIA REUTERS" width="556" height="371"></span></div>
<div class="ds-description"><span class="ds-chapo">Des missiles iraniens &agrave; T&eacute;h&eacute;ran, le 26&nbsp;mars&nbsp;2026.&nbsp;<span class="article__credit" aria-hidden="true">MAJID ASGARIPOUR/WANA VIA REUTERS<br><br>L&rsquo;effet de souffle est &agrave; la hauteur de l&rsquo;effet de surprise. Depuis le 28 f&eacute;vrier, les bombardements men&eacute;s par les Etats-Unis et Isra&euml;l ont, non seulement r&eacute;duit les capacit&eacute;s militaires de leur principal ennemi au Moyen-Orient, mais ont &eacute;galement mis en branle une vaste machinerie guerri&egrave;re impliquant le Liban, les monarchies du Golfe, et les alli&eacute;s de l&rsquo;Iran. Les frappes ont aussi boulevers&eacute; l&rsquo;&eacute;conomie mondiale, avec la fermeture du d&eacute;troit d&rsquo;Ormuz, passage maritime essentiel au commerce mondial d&rsquo;hydrocarbures.</span></span></div>
<div class="ds-description"><span class="ds-chapo"><span class="article__credit" aria-hidden="true">Un mois plus tard, que sait-on du bilan humain et mat&eacute;riel du conflit ? Quels sont les motivations r&eacute;elles des bellig&eacute;rants et leurs objectifs ? Quel impact sur l&rsquo;&eacute;conomie mondiale et la s&eacute;curit&eacute; &eacute;nerg&eacute;tique ? Et surtout, quelles sont les perspectives de paix dans une r&eacute;gion constamment au bord de l&rsquo;escalade ? Les D&eacute;codeurs vous aident &agrave; comprendre les enjeux de cette crise.</span></span></div>
', '2026-04-03 10:00:00', 3) ,     

('Depuis le debut de la guerre en Iran, les hackeurs proches de Teheran monent des attaques opportunistes et la portee limitee' , '<h1 class="ds-title">Depuis le d&eacute;but de la guerre en Iran, les hackeurs proches de T&eacute;h&eacute;ran m&egrave;nent des attaques opportunistes &agrave; la port&eacute;e limit&eacute;e</h1>
<h2>Loin des op&eacute;rations sophistiqu&eacute;es et destructrices que redoutent les diplomaties occidentales, les campagnes iraniennes ont &eacute;t&eacute; pour l&rsquo;heure limit&eacute;es techniquement et principalement &agrave; vis&eacute;e de propagande.</h2>
<p><img src="/image/img_69cb89c4de9c67.84363661.jpeg" alt="" width="556" height="371"></p>
<p>Lors d&rsquo;une comp&eacute;tition de hacking aux &laquo;&nbsp;Jeux olympiques de la tech&nbsp;&raquo;, &agrave; T&eacute;h&eacute;ran, le 28&nbsp;octobre 2025.&nbsp;<span class="article__credit" aria-hidden="true">MORTEZA NIKOUBAZL / NURPHOTO / AFP<br><br>Sur la photographie, Kash Patel n&rsquo;est pas encore le directeur du puissant FBI. Tout sourire, sans doute en vacances, il est assis au bar de La Bodeguita del Medio, l&rsquo;un des hauts lieux touristiques de La Havane. L&rsquo;image fait partie de la dizaine d&rsquo;autres clich&eacute;s personnels et de quelques dizaines de courriels publi&eacute;s, vendredi 27 mars sur Internet, par le groupe de pirates informatiques Handala. Ce dernier, pilot&eacute;, selon plusieurs sources, par le minist&egrave;re du renseignement iranien, a revendiqu&eacute; ainsi le piratage de la bo&icirc;te e-mail personnelle de M. Patel, proche de Donald Trump et chef de la police f&eacute;d&eacute;rale am&eacute;ricaine.<br></span></p>
<p><span class="article__credit" aria-hidden="true">En pleine guerre isra&eacute;lo-am&eacute;ricaine contre l&rsquo;Iran, cette fuite est r&eacute;v&eacute;latrice de la strat&eacute;gie des pirates &oelig;uvrant pour le compte de T&eacute;h&eacute;ran. Au d&eacute;but du conflit, et en raison du lourd passif qui existe entre les parties en la mati&egrave;re, certains avaient craint des attaques sophistiqu&eacute;es et destructrices. Les autorit&eacute;s de cybers&eacute;curit&eacute;&nbsp;<a title="Nouvelle fen&ecirc;tre" href="https://www.ncsc.gov.uk/news/ncsc-advises-uk-organisations-take-action-following-conflict-in-middle-east" target="_blank" rel="noopener">britanniques</a>&nbsp;et&nbsp;<a title="Nouvelle fen&ecirc;tre" href="https://www.cyber.gc.ca/en/guidance/cyber-threat-bulletin-iranian-cyber-threat-response-usisrael-strikes-february-2026" target="_blank" rel="noopener">canadiennes</a>, notamment, avaient publi&eacute; des bulletins d&rsquo;alerte</span></p>', '2026-05-03 10:00:00', 4) , 

('Guerre au Moyen-Orient  le mois qui a plonge le monde dans un choc energetique' , '<p style="text-align: center;"><img src="/image/img_69cb8b758c4fa8.74210248.jpeg" alt="Guerre au Moyen-Orient : le mois qui a plong&eacute; le monde dans un choc &eacute;nerg&eacute;tique" width="1920" height="960"></p>
<h1 class="article__title" style="text-align: center;">Guerre au Moyen-Orient : le mois qui a plong&eacute; le monde dans un choc &eacute;nerg&eacute;tique</h1>
<p>&nbsp;</p>
<h3 style="text-align: center;"><span class="article__kicker">R&eacute;cit</span>Du blocage du d&eacute;troit d&rsquo;Ormuz aux envol&eacute;es du prix du p&eacute;trole et du gaz, en passant par le profit fait par le Kremlin&hellip; Chronologie de la crise &eacute;conomique in&eacute;dite qu&rsquo;a provoqu&eacute;e l&rsquo;attaque am&eacute;ricano-isra&eacute;lienne en Iran, le 28 f&eacute;vrier.</h3>
<p class="article__paragraph article__paragraph--lf" style="text-align: center;">En cette fin f&eacute;vrier, le p&eacute;trole coule &agrave; flots sur les march&eacute;s. Depuis des mois, l&rsquo;offre d&rsquo;or noir d&eacute;passe la demande et tire les prix du baril vers le bas. Quelques secousses agitent bien les cours du brent alors que les Etats-Unis d&eacute;ploient une armada navale et a&eacute;rienne aux abords du golfe Arabo-Persique, mena&ccedil;ant l&rsquo;Iran d&rsquo;une intervention militaire. La zone est critique pour le commerce mondial du p&eacute;trole et du gaz, dont d&rsquo;immenses volumes transitent chaque jour par le d&eacute;troit d&rsquo;Ormuz. Mais jusqu&rsquo;alors, dans l&rsquo;histoire moderne, jamais ce passage &eacute;troit entre l&rsquo;Iran et Oman, principale voie d&rsquo;exportation pour tous les grands producteurs du Golfe, n&rsquo;a &eacute;t&eacute; compl&egrave;tement ferm&eacute;.</p>
<p class="article__paragraph article__paragraph--lf" style="text-align: center;">Washington balaie les craintes des op&eacute;rateurs. <em>&laquo;&nbsp;Le monde est tr&egrave;s bien approvisionn&eacute; en p&eacute;trole &agrave; l&rsquo;heure actuelle&nbsp;&raquo;</em>, affirmait, le 6&nbsp;f&eacute;vrier, le secr&eacute;taire &agrave; l&rsquo;&eacute;nergie am&eacute;ricain, Chris Wright. De quoi donner plus de marge de man&oelig;uvre au pr&eacute;sident Donald Trump, estime-t-il, sans avoir &agrave; <em>&laquo;&nbsp;s&rsquo;inqui&eacute;ter d&rsquo;une flamb&eacute;e folle des prix du p&eacute;trole&nbsp;&raquo;</em>. C&rsquo;&eacute;tait il y a un mois, une &eacute;ternit&eacute;.</p>
<h3 class="article__chapter-title" style="text-align: center;">28&nbsp;f&eacute;vrier : le d&eacute;troit d&rsquo;Ormuz se referme</h3>
<p class="article__paragraph article__paragraph--lf" style="text-align: center;">Ferm&eacute; ou pas ? Samedi 28 f&eacute;vrier, quelques heures apr&egrave;s les premiers bombardements am&eacute;ricano-isra&eacute;liens en Iran, la confusion est &agrave; son comble. Des messages radio &agrave; l&rsquo;origine floue circulent parmi les navires post&eacute;s &agrave; proximit&eacute; du d&eacute;troit d&rsquo;Ormuz : le r&eacute;gime iranien entendrait bloquer le trafic qui passe par cette &eacute;troite voie navigable, reliant les monarchies p&eacute;troli&egrave;res et gazi&egrave;res du Golfe &agrave; l&rsquo;oc&eacute;an Indien, et donc &agrave; leurs principaux march&eacute;s en Asie. Un goulet maritime par lequel transitent chaque jour quelque 20 millions de barils, de brut ou de produits raffin&eacute;s.</p>', '2026-06-03 10:00:00', 2) ;

INSERT INTO article_details(article_id,details) VALUES
(1,2),
(1,3),
(1,4),
(2,1),
(2,3),
(2,4),
(3,1),
(3,2),
(3,4),
(4,1),
(4,2),
(4,3) ;

INSERT INTO article_images(article_id,chemin,alt) VALUES
(1,'/image/article1.jpeg','EN DIRECT, guerre au Moyen-Orient  deux porte-conteneurs chinois ont traverse le detroit d Ormuz lundi  le electricite retablie dans la plupart des quartiers de Teheran') ,
(2,'/image/article2.jpeg','Guerre en Iran  ce qu il faut retenir d un mois de conflit') ,
(3,'/image/article3.jpeg','Depuis le debut de la guerre en Iran, les hackeurs proches de Teheran monent des attaques opportunistes et la portee limitee') ,
(4,'/image/article4.jpeg','Guerre au Moyen-Orient  le mois qui a plonge le monde dans un choc energetique') ;

