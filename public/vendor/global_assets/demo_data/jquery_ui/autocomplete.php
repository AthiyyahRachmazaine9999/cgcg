<?php
sleep( 3 );
// no term passed - just exit early with no response
if (empty($_GET['term'])) exit ;
$q = strtolower($_GET["term"]);
// remove slashes if they were magically added
if (get_magic_quotes_gpc()) $q = stripslashes($q);
$items = array(
"Great Bittern"=>"Botaurus stellaris",
"Little Grebe"=>"Tachybaptus ruficollis",
"Black-necked Grebe"=>"Podiceps nigricollis",
"Little Bittern"=>"Ixobrychus minutus",
"Black-crowned Night Heron"=>"Nycticorax nycticorax",
"Purple Heron"=>"Ardea purpurea",
"White Stork"=>"Ciconia ciconia",
"Spoonbill"=>"Platalea leucorodia",
"Red-crested Pochard"=>"Netta rufina",
"Common Eider"=>"Somateria mollissima",
"Red Kite"=>"Milvus milvus",
"Hen Harrier"=>"Circus cyaneus",
"Montagu`s Harrier"=>"Circus pygargus",
"Black Grouse"=>"Tetrao tetrix",
"Grey Partridge"=>"Perdix perdix",
"Spotted Crake"=>"Porzana porzana",
"Corncrake"=>"Crex crex",
"Common Crane"=>"Grus grus",
"Avocet"=>"Recurvirostra avosetta",
"Stone Curlew"=>"Burhinus oedicnemus",
"Common Ringed Plover"=>"Charadrius hiaticula",
"Kentish Plover"=>"Charadrius alexandrinus",
"Ruff"=>"Philomachus pugnax",
"Common Snipe"=>"Gallinago gallinago",
"Black-tailed Godwit"=>"Limosa limosa",
"Common Redshank"=>"Tringa totanus",
"Sandwich Tern"=>"Sterna sandvicensis",
"Common Tern"=>"Sterna hirundo",
"Arctic Tern"=>"Sterna paradisaea",
"Little Tern"=>"Sternula albifrons",
"Black Tern"=>"Chlidonias niger",
"Barn Owl"=>"Tyto alba",
"Little Owl"=>"Athene noctua",
"Short-eared Owl"=>"Asio flammeus",
"European Nightjar"=>"Caprimulgus europaeus",
"Common Kingfisher"=>"Alcedo atthis",
"Eurasian Hoopoe"=>"Upupa epops",
"Eurasian Wryneck"=>"Jynx torquilla",
"European Green Woodpecker"=>"Picus viridis",
"Crested Lark"=>"Galerida cristata",
"White-headed Duck"=>"Oxyura leucocephala",
"Pale-bellied Brent Goose"=>"Branta hrota",
"Tawny Pipit"=>"Anthus campestris",
"Whinchat"=>"Saxicola rubetra",
"European Stonechat"=>"Saxicola rubicola",
"Northern Wheatear"=>"Oenanthe oenanthe",
"Savi`s Warbler"=>"Locustella luscinioides",
"Sedge Warbler"=>"Acrocephalus schoenobaenus",
"Great Reed Warbler"=>"Acrocephalus arundinaceus",
"Bearded Reedling"=>"Panurus biarmicus",
"Red-backed Shrike"=>"Lanius collurio",
"Great Grey Shrike"=>"Lanius excubitor",
"Woodchat Shrike"=>"Lanius senator",
"Common Raven"=>"Corvus corax",
"Yellowhammer"=>"Emberiza citrinella",
"Ortolan Bunting"=>"Emberiza hortulana",
"Corn Bunting"=>"Emberiza calandra",
"Great Cormorant"=>"Phalacrocorax carbo",
"Hawfinch"=>"Coccothraustes coccothraustes",
"Common Shelduck"=>"Tadorna tadorna",
"Bluethroat"=>"Luscinia svecica",
"Grey Heron"=>"Ardea cinerea",
"Barn Swallow"=>"Hirundo rustica",
"Hooded Crow"=>"Corvus cornix",
"Dunlin"=>"Calidris alpina",
"Eurasian Pied Flycatcher"=>"Ficedula hypoleuca",
"Eurasian Nuthatch"=>"Sitta europaea",
"Short-toed Tree Creeper"=>"Certhia brachydactyla",
"Wood Lark"=>"Lullula arborea",
"Tree Pipit"=>"Anthus trivialis",
"Eurasian Hobby"=>"Falco subbuteo",
"Marsh Warbler"=>"Acrocephalus palustris",
"Wood Sandpiper"=>"Tringa glareola",
"Tawny Owl"=>"Strix aluco",
"Lesser Whitethroat"=>"Sylvia curruca",
"Barnacle Goose"=>"Branta leucopsis",
"Common Goldeneye"=>"Bucephala clangula",
"Western Marsh Harrier"=>"Circus aeruginosus",
"Common Buzzard"=>"Buteo buteo",
"Sanderling"=>"Calidris alba",
"Little Gull"=>"Larus minutus",
"Eurasian Magpie"=>"Pica pica",
"Willow Warbler"=>"Phylloscopus trochilus",
"Wood Warbler"=>"Phylloscopus sibilatrix",
"Great Crested Grebe"=>"Podiceps cristatus",
"Eurasian Jay"=>"Garrulus glandarius",
"Common Redstart"=>"Phoenicurus phoenicurus",
"Blue-headed Wagtail"=>"Motacilla flava",
"Common Swift"=>"Apus apus",
"Marsh Tit"=>"Poecile palustris",
"Goldcrest"=>"Regulus regulus",
"European Golden Plover"=>"Pluvialis apricaria",
"Eurasian Bullfinch"=>"Pyrrhula pyrrhula",
"Common Whitethroat"=>"Sylvia communis",
"Meadow Pipit"=>"Anthus pratensis",
"Greylag Goose"=>"Anser anser",
"Spotted Flycatcher"=>"Muscicapa striata",
"European Greenfinch"=>"Carduelis chloris",
"Common Greenshank"=>"Tringa nebularia",
"Great Spotted Woodpecker"=>"Dendrocopos major",
"Greater Canada Goose"=>"Branta canadensis",
"Mistle Thrush"=>"Turdus viscivorus",
"Great Black-backed Gull"=>"Larus marinus",
"Goosander"=>"Mergus merganser",
"Great Egret"=>"Casmerodius albus",
"Northern Goshawk"=>"Accipiter gentilis",
"Dunnock"=>"Prunella modularis",
"Stock Dove"=>"Columba oenas",
"Common Wood Pigeon"=>"Columba palumbus",
"Eurasian Woodcock"=>"Scolopax rusticola",
"House Sparrow"=>"Passer domesticus",
"Common House Martin"=>"Delichon urbicum",
"Red Knot"=>"Calidris canutus",
"Western Jackdaw"=>"Corvus monedula",
"Brambling"=>"Fringilla montifringilla",
"Northern Lapwing"=>"Vanellus vanellus",
"European Reed Warbler"=>"Acrocephalus scirpaceus",
"Lesser Black-backed Gull"=>"Larus fuscus",
"Little Egret"=>"Egretta garzetta",
"Little Stint"=>"Calidris minuta",
"Common Linnet"=>"Carduelis cannabina",
"Mute Swan"=>"Cygnus olor",
"Common Cuckoo"=>"Cuculus canorus",
"Black-headed Gull"=>"Larus ridibundus",
"Greater White-fronted Goose"=>"Anser albifrons",
"Great Tit"=>"Parus major",
"Redwing"=>"Turdus iliacus",
"Gadwall"=>"Anas strepera",
"Fieldfare"=>"Turdus pilaris",
"Tufted Duck"=>"Aythya fuligula",
"Crested Tit"=>"Lophophanes cristatus",
"Willow Tit"=>"Poecile montanus",
"Eurasian Coot"=>"Fulica atra",
"Common Blackbird"=>"Turdus merula",
"Smew"=>"Mergus albellus",
"Common Sandpiper"=>"Actitis hypoleucos",
"Sand Martin"=>"Riparia riparia",
"Purple Sandpiper"=>"Calidris maritima",
"Northern Pintail"=>"Anas acuta",
"Blue Tit"=>"Cyanistes caeruleus",
"European Goldfinch"=>"Carduelis carduelis",
"Eurasian Whimbrel"=>"Numenius phaeopus",
"Common Reed Bunting"=>"Emberiza schoeniclus",
"Eurasian Tree Sparrow"=>"Passer montanus",
"Rook"=>"Corvus frugilegus",
"European Robin"=>"Erithacus rubecula",
"Bar-tailed Godwit"=>"Limosa lapponica",
"Dark-bellied Brent Goose"=>"Branta bernicla",
"Eurasian Oystercatcher"=>"Haematopus ostralegus",
"Eurasian Siskin"=>"Carduelis spinus",
"Northern Shoveler"=>"Anas clypeata",
"Eurasian Wigeon"=>"Anas penelope",
"Eurasian Sparrow Hawk"=>"Accipiter nisus",
"Icterine Warbler"=>"Hippolais icterina",
"Common Starling"=>"Sturnus vulgaris",
"Long-tailed Tit"=>"Aegithalos caudatus",
"Ruddy Turnstone"=>"Arenaria interpres",
"Mew Gull"=>"Larus canus",
"Common Pochard"=>"Aythya ferina",
"Common Chiffchaff"=>"Phylloscopus collybita",
"Greater Scaup"=>"Aythya marila",
"Common Kestrel"=>"Falco tinnunculus",
"Garden Warbler"=>"Sylvia borin",
"Eurasian Collared Dove"=>"Streptopelia decaocto",
"Eurasian Skylark"=>"Alauda arvensis",
"Common Chaffinch"=>"Fringilla coelebs",
"Common Moorhen"=>"Gallinula chloropus",
"Water Pipit"=>"Anthus spinoletta",
"Mallard"=>"Anas platyrhynchos",
"Winter Wren"=>"Troglodytes troglodytes",
"Common Teal"=>"Anas crecca",
"Green Sandpiper"=>"Tringa ochropus",
"White Wagtail"=>"Motacilla alba",
"Eurasian Curlew"=>"Numenius arquata",
"Song Thrush"=>"Turdus philomelos",
"European Herring Gull"=>"Larus argentatus",
"Grey Plover"=>"Pluvialis squatarola",
"Carrion Crow"=>"Corvus corone",
"Coal Tit"=>"Periparus ater",
"Spotted Redshank"=>"Tringa erythropus",
"Blackcap"=>"Sylvia atricapilla",
"Egyptian Vulture"=>"Neophron percnopterus",
"Razorbill"=>"Alca torda",
"Alpine Swift"=>"Apus melba",
"Long-legged Buzzard"=>"Buteo rufinus",
"Audouin`s Gull"=>"Larus audouinii",
"Balearic Shearwater"=>"Puffinus mauretanicus",
"Upland Sandpiper"=>"Bartramia longicauda",
"Greater Spotted Eagle"=>"Aquila clanga",
"Ring Ouzel"=>"Turdus torquatus",
"Yellow-browed Warbler"=>"Phylloscopus inornatus",
"Blue Rock Thrush"=>"Monticola solitarius",
"Buff-breasted Sandpiper"=>"Tryngites subruficollis",
"Jack Snipe"=>"Lymnocryptes minimus",
"White-rumped Sandpiper"=>"Calidris fuscicollis",
"Ruddy Shelduck"=>"Tadorna ferruginea",
"Cetti's Warbler"=>"Cettia cetti",
"Citrine Wagtail"=>"Motacilla citreola",
"Roseate Tern"=>"Sterna dougallii",
"Black-legged Kittiwake"=>"Rissa tridactyla",
"Pygmy Cormorant"=>"Phalacrocorax pygmeus",
"Heuglin's Gull"=>"Larus heuglini"
);
$result = array();
foreach ($items as $key=>$value) {
	if (strpos(strtolower($key), $q) !== false) {
		array_push($result, array("id"=>$value, "label"=>$key, "value" => strip_tags($key)));
	}
	if (count($result) > 11)
		break;
}
// json_encode is available in PHP 5.2 and above, or you can install a PECL module in earlier versions
echo json_encode($result);
?>