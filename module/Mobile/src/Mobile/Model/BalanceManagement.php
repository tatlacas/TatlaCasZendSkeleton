<?php
/**
 * Created by PhpStorm.
 * User: tatenda
 * Date: 29/9/15
 * Time: 1:28 PM
 */

namespace Mobile\Model;


use Application\Entity\EcocashPayments;
use Application\Entity\FriendsPayments;
use Application\Entity\FromNettcashServer;
use Application\Entity\NettcashAccounts;
use Application\Entity\NettcashPayments;
use Application\Entity\PackagePlans;
use Application\Entity\SubscribedPackages;
use Application\Entity\UserActivitiesData;
use Application\Entity\UserDependents;
use Application\Entity\UserPayments;
use Application\Entity\Users;
use Application\Model\Constants;
use Application\Model\DoctrineInitialization;
use Application\Model\infobipSMSMessaging;
use Application\Entity\AdminUpdates;

class BalanceManagement extends DoctrineInitialization
{
    private $field_a;
    private $field_b;
    private $field_c;
    private $field_d;
    const SHIRI_FUNERAL_PLAN_PAYMENT = 'Shiri Funeral Plan Payment';


    /**
     * BalanceManagement constructor.
     * @param $service_locator
     * @param $field_a
     * @param $field_b
     * @param $field_c
     * @param $field_d
     */
    public function __construct($service_locator, $field_a, $field_b, $field_c, $field_d)
    {
        parent::__construct($service_locator);
        $this->field_a = $field_a;
        $this->field_b = $field_b;
        $this->field_c = $field_c;
        $this->field_d = $field_d;
    }

    public function getOwningBalance()
    {
        $this->setEntityManager();

        $user = $this->entity_manager->getRepository(Constants::ENTITY_USERS)->findOneByPhoneNumber($this->field_a);
        $subscribed_packages = $this->entity_manager->getRepository(Constants::ENTITY_SUBSCRIBED_PACKAGES)->findBy(array('user'=>$user,'status'=>true));
        $result = '<owing_balances>';
        foreach($subscribed_packages as $package)
        {

            //retrieve last payment of each $package in user_payments
            $last_payment = $this->entity_manager->getRepository(Constants::ENTITY_USER_PAYMENTS)->findBy(array('subscribedPackage'=>$package) ,array('monthPaidFor' => 'DESC'), 1);
            //if $last_payment is NULL then user hasnt paid for any month since joining, calucate figures sicen joining gettting values from payment_figures for each month, adding
            //generate a loop from start to end date eg $result .= '<to_pay is_Dependent="1" for_date="14444555" amount="300"/>
        }
//get values for terminated subscriptions
        $subscribed_packages = $this->entity_manager->getRepository(Constants::ENTITY_SUBSCRIBED_PACKAGES)->findBy(array('user'=>$user,'status'=>false));
        foreach($subscribed_packages as $package)
        {

            //retrieve last payment of each $package in user_payments
            $last_payment = $this->entity_manager->getRepository(Constants::ENTITY_USER_PAYMENTS)->findBy(array('subscribedPackage'=>$package) ,array('monthPaidFor' => 'DESC'), 1);
            //compare last date paid for with date termiated, if last date paid for is less than date terminated then there are payments owing!!, add values for those months
        }


        $result .= '</owing_balances>';


        return $result;
    }

    private function dummy()
    {
        $this->setEntityManager();

        $brnach = $this->entity_manager->getRepository(Constants::ENTITY_BRANCHES)->findOneById(12);
        $user1 = $this->entity_manager->getRepository(Constants::ENTITY_USERS)->findOneByUserId(1);
        $names = array(
            "Jessie" => "Montgomery",
            "Darrin" => "Gill",
            "Sheila" => "Moran",
            "Belinda" => "Mathis",
            "Stacey" => "Willis",
            "Essie" => "Sanders",
            "Violet" => "Howell",
            "Lauren" => "Pittman",
            "Erik" => "Christensen",
            "Danielle" => "Wilson",
            "Casey" => "Harris",
            "Lillie" => "Butler",
            "Elbert" => "Dunn",
            "Emily" => "Mcgee",
            "Stacy" => "Rice",
            "Cedric" => "Griffith",
            "Pat" => "Jones",
            "Iris" => "Olson",
            "Roderick" => "Wright",
            "Cody" => "Hopkins",
            "Andrea" => "Carroll",
            "Clint" => "Brooks",
            "Daniel" => "Hudson",
            "Felipe" => "Jimenez",
            "Patty" => "Long",
            "Horace" => "Stone",
            "Helen" => "Cobb",
            "Edna" => "Mckenzie",
            "Denise" => "Erickson",
            "Madeline" => "Greene",
            "Eileen" => "Coleman",
            "Georgia" => "Ford",
            "Emma" => "Hicks",
            "Alma" => "Barrett",
            "Cecelia" => "Lindsey",
            "Carl" => "Sullivan",
            "Kerry" => "Black",
            "Benny" => "Saunders",
            "Daisy" => "Lawrence",
            "Tracy" => "Figueroa",
            "Meredith" => "Moody",
            "Ebony" => "Bowman",
            "Jackie" => "Schwartz",
            "Silvia" => "Little",
            "Melody" => "Davidson",
            "Tiffany" => "Silva",
            "Andy" => "Wong",
            "Kelli" => "Carter",
            "Michelle" => "Bishop",
            "Wilfred" => "Woods",
            "Myron" => "Fields",
            "Dwayne" => "Alvarez",
            "Mabel" => "Fernandez",
            "Judith" => "Reeves",
            "Johnnie" => "Green",
            "Alison" => "Ferguson",
            "Nelson" => "Stokes",
            "Courtney" => "Powers",
            "Jimmie" => "Andrews",
            "Eloise" => "Duncan",
            "Sarah" => "Clayton",
            "Vernon" => "Byrd",
            "Ervin" => "Marsh",
            "Irene" => "Kim",
            "Felicia" => "Massey",
            "Adrienne" => "Houston",
            "Esther" => "Mullins",
            "Vera" => "Castillo",
            "Willie" => "Fletcher",
            "Ralph" => "Newman",
            "Karl" => "Rios",
            "Gilberto" => "Freeman",
            "Jim" => "Miles",
            "Tonya" => "Moore",
            "Catherine" => "Romero",
            "Josephine" => "Porter",
            "Shelly" => "Steele",
            "Kyle" => "Bell",
            "Colleen" => "Huff",
            "Darryl" => "Summers",
            "Sonja" => "Riley",
            "Donald" => "Mclaughlin",
            "Sonya" => "Adkins",
            "Lana" => "Austin",
            "Angelo" => "Gonzalez",
            "Carla" => "Sutton",
            "Maggie" => "Wood",
            "Franklin" => "Estrada",
            "Elias" => "Gross",
            "Rene" => "Holland",
            "Natasha" => "Pierce",
            "Brittany" => "Jennings",
            "Lewis" => "Nash",
            "Kim" => "Douglas",
            "Lula" => "Scott",
            "Guillermo" => "Vargas",
            "Sophia" => "Harmon",
            "Harold" => "Blair",
            "Craig" => "West",
            "Drew" => "Johnson",
            "Tony" => "Drake",
            "Marilyn" => "Rodriguez",
            "Raul" => "Glover",
            "Hugo" => "Morris",
            "Melanie" => "Clark",
            "Leigh" => "Hammond",
            "Judy" => "Lewis",
            "Gladys" => "Briggs",
            "Bernice" => "Klein",
            "Constance" => "Wilkins",
            "Darryline" => "Fields",
            "Renege" => "Walton",
            "Clinton" => "Mccormick",
            "Julio" => "Wells",
            "Philip" => "Mack",
            "Jody" => "Ross",
            "Gregg" => "Mcdonald",
            "Bennie" => "Lee",
            "Wilbur" => "Griffin",
            "Agnes" => "Fowler",
            "Inez" => "Snyder",
            "Rose" => "Adkins",
            "Levi" => "Leonard",
            "Hilda" => "Nash",
            "Wilfredfry" => "Peterson",
            "Blanche" => "Valdez",
            "Latoya" => "Pearson",
            "Faith" => "Salazar",
            "Kelly" => "Fitzgerald",
            "Jill" => "Stephens",
            "Howard" => "Owen",
            "Nettie" => "Gross",
            "Francis" => "Higgins",
            "Geoffrey" => "Hines",
            "Delores" => "Moody",
            "Saul" => "Keller",
            "Louis" => "Miles",
            "Clyde" => "Lloyd",
            "Jennifer" => "Reid",
            "Nicholas" => "Mccoy",
            "Joseph" => "Hodges",
            "Ella" => "Rose",
            "Shelley" => "Jennings",
            "Sherry" => "Santiago",
            "Carlorine" => "Goodman",
            "Elbertine" => "Perez",
            "Terri" => "Stokes",
            "Hugh" => "Mccarthy",
            "Glenn" => "Harrison",
            "Lillian" => "Bennett",
            "Evan" => "Porter",
            "Geneva" => "Woods",
            "Andyer" => "Aguilar",
            "Faye" => "Barber",
            "Olive" => "Alvarado",
            "Bobby" => "Alvarez",
            "Archie" => "Jimenez",
            "Mercedes" => "Poole",
            "Malcolm" => "Goodwin",
            "Yvette" => "Gordon",
            "Peggy" => "Arnold",
            "Alan" => "Jones",
            "Tanya" => "Curry",
            "Vivian" => "Griffith",
            "Andreas" => "Martinez",
            "Michelles" => "Medina",
            "Brenda" => "Mclaughlin",
            "Tamara" => "Tate",
            "Terry" => "Walker",
            "Carmen" => "Schneider",
            "Jermaine" => "Rivera",
            "Teri" => "Burns",
            "Olga" => "Guzman",
            "Terence" => "Patton",
            "Byron" => "Lawson",
            "Mona" => "Zimmerman",
            "Charles" => "Park",
            "Walter" => "Strickland",
            "Heleny" => "Shaw",
            "Lucy" => "Little",
            "Kurt" => "Douglas",
            "Juana" => "Lyons",
            "Jessieas" => "Day",
            "Lynette" => "Sherman",
            "Bradley" => "Collier",
            "Tom" => "Ramos",
            "Stuart" => "Turner",
            "Phil" => "Luna",
            "Sergio" => "Larson",
            "Marcos" => "Bass",
            "Elsie" => "Hunter",
            "Daryl" => "Potter",
            "Bridget" => "Sandoval",
            "Spencer" => "Herrera",
            "Kirk" => "Osborne",
            "Charlie" => "Payne",
            "Al" => "Silva",
            "Cristina" => "Jenkins",
            "Jacqueline" => "Frazier",
            "Lilliew" => "Cruz",
            "Janessa" => "Beckman",
            "Ezequiel" => "Mcclure",
            "Stevie" => "Mccarty",
            "Cecile" => "Becerra",
            "Carey" => "Gandy",
            "Sofia" => "Henley",
            "Tessa" => "Washburn",
            "Lanita" => "Cass",
            "Lucila" => "Bruce",
            "Humberto" => "Bustamante",
            "Marisol" => "Arredondo",
            "Leisha" => "Creamer",
            "Marquis" => "Marroquin",
            "Georgann" => "Swain",
            "Ione" => "Sauls",
            "Sharmaine" => "Combs",
            "Cuc" => "Hanes",
            "Antone" => "Paradis",
            "Palma" => "Layton",
            "Ahmed" => "Mckay",
            "Jone" => "Corrigan",
            "Pamila" => "Browning",
            "Deloris" => "Rosario",
            "Lacey" => "Nettles",
            "Nena" => "Morrissey",
            "Caroyln" => "Craft",
            "Erwin" => "Rossi",
            "Krystin" => "Lindstrom",
            "Matthew" => "Button",
            "Floy" => "Carden",
            "Rosamaria" => "Ligon",
            "Belen" => "Kenyon",
            "Lizette" => "Valenzuela",
            "Valentine" => "Bueno",
            "Newton" => "Falk",
            "Jeanice" => "Donovan",
            "Larisa" => "Tompkins",
            "Vernell" => "Gardiner",
            "Beckie" => "Conners",
            "Racquel" => "Bolt",
            "Dionna" => "Conaway",
            "Isadora" => "Coronado",
            "Alvina" => "Cone",
            "Aline" => "Singh",
            "Stefania" => "Garland",
            "Gilberte" => "Mccullough",
            "Ramonita" => "Bone",
            "Christopher" => "Mccall",
            "Donnell" => "Hyde",
            "Zelma" => "Stamps",
            "Joel" => "Boston",
            "Wilburn" => "Somers",
            "Maribeth" => "Chisolm",
            "Josephina" => "Hitt",
            "Joslyn" => "Lantz",
            "Jackqueline" => "Tuck",
            "Breanna" => "Guerin",
            "Virgil" => "Holbrook",
            "Marti" => "Apodaca",
            "Lawana" => "Joyce",
            "Jerrold" => "Hood",
            "Helga" => "Parrish",
            "Joanie" => "Stanford",
            "Narcisa" => "Dial",
            "Julieann" => "Elam",
            "Gonzalo" => "Purnell",
            "Yael" => "Hammonds",
            "Shemeka" => "Balderas",
            "Ermelinda" => "Wilkes",
            "Vella" => "Burroughs",
            "Aleshia" => "Burgos",
            "Sandee" => "Hoppe",
            "Bertram" => "Lively",
            "Sirena" => "Delgadillo",
            "Lurline" => "Sledge",
            "Rowena" => "Seibert",
            "Darlena" => "Denning",
            "Houston" => "Cardwell",
            "Lida" => "Kaplan",
            "Jaye" => "Stroud",
            "Barbie" => "Easterling",
            "Amie" => "Reinhart",
            "Kasha" => "Flynn",
            "Adell" => "Rounds",
            "Quyen" => "Wimberly",
            "Hye" => "Maxey",
            "Magnolia" => "Hollis",
            "Blake" => "Holder",
            "Mohammad" => "Alonzo",
            "Danyel" => "Sturgill",
            "Brian" => "Steel",
            "Tori" => "Scroggins",
            "Carie" => "Luciano",
            "Ericka" => "Counts",
            "Kerstin" => "Quintana",
            "Paulina" => "Magana",
            "Rosalind" => "Fanning",
            "Shannan" => "Farrell",
            "Jonelle" => "Paz",
            "Joesph" => "Comer",
            "Vern" => "Sessions",
            "Lucretia" => "Adamson",
            "Anisa" => "Callahan",
            "Ethelene" => "House",
            "Lue" => "Meade",
            "Brooks" => "Neel",
            "Ginette" => "Velasquez",
            "Honey" => "Shuler",
            "Danial" => "Birch",
            "Kenya" => "Cowart",
            "Tayna" => "See",
            "Ivelisse" => "Finnegan",
            "Keturah" => "Leavitt",
            "Margherita" => "Schreiber",
            "Michaele" => "Sage",
            "Letisha" => "Orourke",
            "Tomeka" => "Jolley",
            "Robbie" => "Mccorkle",
            "Lavonne" => "Dolan",
            "Regan" => "Lange",
            "Jennette" => "Pendergrass",
            "Donte" => "Turk",
            "Reda" => "Villarreal",
            "Ellie" => "Meek",
            "Lore" => "Burch",
            "Kathlene" => "Baxley",
            "Christinia" => "Contreras",
            "Glayds" => "Butcher",
            "Britt" => "Carrillo",
            "Pei" => "Conway",
            "Gussie" => "Bentley",
            "Chet" => "Ashmore",
            "Tanesha" => "Mcbee",
            "Diann" => "Levy",
            "Nereida" => "Stacy",
            "Juliette" => "Aguilera",
            "Raguel" => "Madsen",
            "Earle" => "Devine",
            "Anamaria" => "Sorenson",
            "Linn" => "Youngblood",
            "Micah" => "Stanford",
            "Chi" => "Pinkerton",
            "Dylan" => "Henley",
            "Stefani" => "Bills",
            "Sherryl" => "Musser",
            "Katelynn" => "Flannery",
            "Leonila" => "Glynn",
            "Lianne" => "Mullis",
            "Cory" => "Hammonds",
            "Blossom" => "Teeter",
            "Katlyn" => "Laughlin",
            "Leanora" => "Seiler",
            "Thomas" => "Wheaton",
            "Reggie" => "Inman",
            "Marybelle" => "Homer",
            "Rhiannon" => "Matos",
            "Gerda" => "Drury",
            "Mabelle" => "Beaulieu",
            "Marge" => "Holly",
            "Karima" => "Bergstrom",
            "Fanny" => "Gladney",
            "Steffanie" => "Tidwell",
            "Marlys" => "Culver",
            "Bruna" => "Smothers",
            "Annabell" => "Winkler",
            "Johnie" => "Spruill",
            "Latina" => "Wooten",
            "Melani" => "Acker",
            "Liberty" => "Desimone",
            "Francie" => "Clement",
            "Aleen" => "Cooks",
            "Shu" => "Hirsch",
            "Deedra" => "Devito",
            "Michelina" => "Dykes",
            "Kemberly" => "Huang",
            "Enid" => "Heath",
            "Jc" => "Hightower",
            "Sonny" => "Hutton",
            "Deidra" => "Quigley",
            "Sandeey" => "Halcomb",
            "Leone" => "Siler",
            "Laurette" => "Dortch",
            "Petrina" => "Pinkston",
            "Zina" => "Simonson",
            "Candance" => "Cline",
            "Sammie" => "Wilt",
            "Ty" => "Bumgarner",
            "Haley" => "Mcclanahan",
            "Argelia" => "Utley",
            "Judson" => "Crump",
            "Judie" => "Nagel",
            "Valorie" => "Corral",
            "Lona" => "Tillman",
            "Tawna" => "Kiser",
            "Jenell" => "Hudgins",
            "Danyelle" => "Velez",
            "Bettyann" => "Atwood",
            "Bobbie" => "Bandy",
            "Clare" => "Muhammad",
            "Kalyn" => "Strand",
            "Theresa" => "Green",
            "Ronald" => "Thompson",
            "Janice" => "Rogers",
            "Gregory" => "Hall",
            "Norma" => "Ward",
            "Stephen" => "Gonzalez",
            "Patricia" => "Rivera",
            "Mark" => "Lopez",
            "Barbara" => "Simmons",
            "Alanll" => "Evans",
            "Melissa" => "Jackson",
            "Robert" => "Washington",
            "Marilynu" => "Ramirez",
            "Lisa" => "Garcia",
            "Jimmy" => "Ross",
            "Martin" => "Perez",
            "Albert" => "Collins",
            "Phillip" => "Wright",
            "Eugene" => "Griffin",
            "Gerald" => "James",
            "Anne" => "Sanchez",
            "Steve" => "Sanders",
            "Virginia" => "Brooks",
            "Joyce" => "Smith",
            "Joe" => "Johnson",
            "Joan" => "Richardson",
            "Kathryn" => "King",
            "Gloria" => "White",
            "Benjamin" => "Coleman",
            "Helenitrate" => "Mitchell",
            "Ruth" => "Taylor",
            "Christopherine" => "Brown",
            "Kathleen" => "Cooper",
            "Ireney" => "Peterson",
            "Danielie" => "Cox",
            "Earl" => "Bryant",
            "Jeffrey" => "Alexander",
            "Clarence" => "Turner",
            "Christina" => "Stewart",
            "Kevin" => "Patterson",
            "Marie" => "Hernandez",
            "Russell" => "Scott",
            "Thomasus" => "Kelly",
            "Ann" => "Bailey",
            "Angela" => "Carter",
            "Jack" => "Reed",
            "Janet" => "Thomas",
            "Edward" => "Perry",
            "Louise" => "Jenkins",
            "Adam" => "Edwards",
            "Julia" => "Walker",
            "Jacqueliney" => "Davis",
            "Christine" => "Young",
            "Mildred" => "Williams",
            "Ernest" => "Morgan",
            "Larry" => "Watson",
            "George" => "Murphy",
            "Williem" => "Barnes",
            "Josephinepine" => "Russell",
            "Donaldino" => "Campbell",
            "Diana" => "Anderson",
            "Kimberly" => "Miller",
            "Jean" => "Martinez",
            "John" => "Bennett",
            "Randy" => "Phillips",
            "Jessica" => "Allen",
            "Elizabeth" => "Clark",
            "Paul" => "Gonzales",
            "Juan" => "Wilson",
            "Beverly" => "Baker",
            "Matthewa" => "Hughes",
            "Justin" => "Howard",
            "Jenniferr" => "Gray",
            "Michael" => "Foster",
            "Jose" => "Lee",
            "David" => "Rodriguez",
            "Katherine" => "Bell",
            "Alesia" => "Lewis",
            "Nancy" => "Price",
            "Anna" => "Powell",
            "Joshua" => "Butler",
            "Heather" => "Torres",
            "Henry" => "Flores",
            "Pamela" => "Nelson",
            "Tawnya" => "Morris",
            "Roger" => "Hill",
            "Brandon" => "Moore",
            "Carol" => "Henderson",
            "Verda" => "Wood",
            "Laura" => "Harris",
            "Sharon" => "Robinson",
            "Dennis" => "Cook",
            "Anthony" => "Roberts",
            "Katia" => "Adams",
            "Luanne" => "Martin",
            "James" => "Jones",
            "Rebecca" => "Long",
            "Kenneth" => "Diaz",
            "Jane" => "Parker",
            "Ashley" => "Thompson",
        );

        $phones = array(
            "047274130059",
            "213302571952",
            "205189813624",
            "019177270726",
            "717131931473",
            "415554399712",
            "511548488784",
            "912355876510",
            "332681846949",
            "547187785177",
            "327774130826",
            "314210742031",
            "484543513192",
            "709481860899",
            "230860899326",
            "026739035162",
            "425587290218",
            "731879295907",
            "090288262179",
            "582547580951",
            "444395363967",
            "211421766927",
            "925788425570",
            "244010241064",
            "647342753968",
            "755618772488",
            "750249798753",
            "442794879602",
            "182185050533",
            "159143200127",
            "717346870821",
            "197987314376",
            "851862571523",
            "346900333432",
            "064992914709",
            "072183242549",
            "529176899012",
            "289320637467",
            "338617231026",
            "228671041552",
            "585623021715",
            "267849179541",
            "429301825588",
            "010522826539",
            "196673744619",
            "271846710982",
            "376161107901",
            "476736769006",
            "945705219762",
            "949594902446",
            "270505845067",
            "939539864127",
            "886605464789",
            "534172044055",
            "070196118795",
            "104139864191",
            "838091853618",
            "279381859801",
            "475268999296",
            "106403890572",
            "947729192189",
            "446596402261",
            "655082386520",
            "014284713613",
            "267828898002",
            "941351046261",
            "320793964718",
            "938505879477",
            "361843541553",
            "376319320114",
            "554267402207",
            "480657333696",
            "412599164613",
            "947928592233",
            "847547901593",
            "134219297989",
            "565680076716",
            "855941714131",
            "215021655655",
            "965430293920",
            "145238655419",
            "336631561749",
            "690915810497",
            "035126014762",
            "115776084371",
            "795193132469",
            "034782567692",
            "385122027054",
            "315689833746",
            "858182702180",
            "152703067853",
            "059078638761",
            "913728966080",
            "019368068364",
            "590140381139",
            "501837433376",
            "508591784624",
            "003625370997",
            "455244864428",
            "409390247962",
            "125702760892",
            "649653421692",
            "953769098976",
            "388568290111",
            "705397896780",
            "518009103800",
            "925774371462",
            "566888205514",
            "489519267171",
            "936467197608",
            "338881514312",
            "098845128164",
            "802258911134",
            "519279805194",
            "574981336521",
            "317407270068",
            "241539570513",
            "265652487553",
            "044611692077",
            "660029023913",
            "274497746828",
            "191127558821",
            "946469713018",
            "268249594043",
            "966896790483",
            "419254349756",
            "344669724590",
            "172858565523",
            "720045693893",
            "604309876349",
            "203763162522",
            "811181451180",
            "208402796218",
            "479329128121",
            "200853292703",
            "954678487777",
            "049611210008",
            "508142417461",
            "166875700025",
            "782852838852",
            "671826356115",
            "143702696772",
            "799395375413",
            "200109500512",
            "542699975567",
            "380525111833",
            "018617376565",
            "055704283546",
            "672956834257",
            "441051945425",
            "402299898329",
            "929075928774",
            "687003736764",
            "+44775378397",
            "338955430767",
            "115875927205",
            "748443887747",
            "258798170362",
            "067076685665",
            "696991863623",
            "201686956078",
            "542697223281",
            "243918645607",
            "437627086405",
            "122143811475",
            "057807075718",
            "160833972058",
            "830210243114",
            "717046301021",
            "015157947902",
            "348459195719",
            "342076558373",
            "828814311067",
            "425850794484",
            "301752177828",
            "873328686339",
            "454566997640",
            "657168964953",
            "209863683723",
            "954064708599",
            "769556002541",
            "244790090072",
            "593093639576",
            "154130696737",
            "876942491841",
            "515552265659",
            "151244268802",
            "934487114990",
            "426765798954",
            "968004512482",
            "858482504916",
            "395431971384",
            "741035805119",
            "204929019039",
            "527219710181",
            "385069788329",
            "739006356211",
            "715148023532",
            "914505954123",
            "745953836243",
            "927340962168",
            "665505517800",
            "921713027544",
            "524004817280",
            "298089998893",
            "267736356383",
            "167871201582",
            "709354432119",
            "579097641454",
            "186945524494",
            "164238686609",
            "020946743785",
            "446603734188",
            "275576538859",
            "206252524640",
            "901439603960",
            "191175478074",
            "137164711825",
            "976686464720",
            "177360535351",
            "241404397532",
            "580580798559",
            "462703710420",
            "787536738585",
            "121944319982",
            "549876072794",
            "316309261289",
            "585516563167",
            "491560326196",
            "711707946258",
            "377085874545",
            "122495595932",
            "037171395142",
            "794379097091",
            "174439334455",
            "549520598456",
            "634173987617",
            "061797961558",
            "043201595772",
            "115715022068",
            "699608803260",
            "107311958930",
            "193400916541",
            "154751184927",
            "796515672933",
            "470452544506",
            "773040514430",
            "182978007176",
            "234002894814",
            "160461608717",
            "313623743977",
            "205228583072",
            "085765064048",
            "517980913427",
            "216283022861",
            "113065493467",
            "421119588661",
            "940942416176",
            "761742199463",
            "892559434091",
            "390408735246",
            "725002358568",
            "824639882830",
            "140876703775",
            "891228180280",
            "791331442689",
            "220297557823",
            "799486109527",
            "753824996362",
            "137996573789",
            "158793348988",
            "918705911583",
            "553561481285",
            "807077246616",
            "503563730054",
            "513187085225",
            "050638823021",
            "962540862858",
            "482680198552",
            "+27761998088",
            "580838627042",
            "776238545696",
            "636841812317",
            "674093044229",
            "075143167446",
            "+27616302895",
            "143957466960",
            "397099188566",
            "893090089272",
            "855306518798",
            "533251585446",
            "482470378328",
            "053099786034",
            "126504506379",
            "725448917092",
            "580085278749",
            "526192120598",
            "148925396031",
            "555674663563",
            "032643876435",
            "844232735753",
            "915892387625",
            "708013492145",
            "232288393497",
            "732126440259",
            "784543929237",
            "277349391579",
            "779253396368",
            "359140427331",
            "031153012402",
            "310580127984",
            "449752196380",
            "337228214870",
            "012384831017",
            "217797871038",
            "756009667589",
            "804564751793",
            "655532173100",
            "673839427671",
            "980756010351",
            "904283721286",
            "070323014295",
            "053970102109",
            "451110380527",
            "557106548651",
            "782515056807",
            "851708089217",
            "301930967994",
            "806303418305",
            "343180863651",
            "794201931693",
            "210838839996",
            "752356004374",
            "945487642144",
            "740562643572",
            "716099965087",
            "678218228037",
            "324667624135",
            "208617181150",
            "936354256250",
            "046799714088",
            "578417635624",
            "598103907453",
            "349743242213",
            "058141866850",
            "857665084616",
            "717136122079",
            "651006209137",
            "338993660981",
            "829729919187",
            "282396858477",
            "432970997028",
            "966264579994",
            "544564470890",
            "975585670140",
            "731070499521",
            "442119006878",
            "083868086019",
            "783677582570",
            "687902744157",
            "305412653439",
            "319428404782",
            "868049068288",
            "084899662046",
            "474724214276",
            "824839788780",
            "326480415371",
            "328083233495",
            "922677375113",
            "677339785205",
            "121520644568",
            "162113651397",
            "669762526880",
            "885310970740",
            "113364287207",
            "288359496578",
            "113288283914",
            "410220224581",
            "831221230786",
            "608995661292",
            "286727946305",
            "700091339904",
            "575784971735",
            "731691161813",
            "614472331860",
            "184285109294",
            "080887786401",
            "696466289203",
            "632035044931",
            "566065563052",
            "149171578157",
            "828226135546",
            "510477095021",
            "776024149861",
            "475850361054",
            "771763953608",
            "237619696213",
            "451222153809",
            "463949337161",
            "167430691838",
            "499976977465",
            "376545844178",
            "383291848396",
            "558045177792",
            "532219113874",
            "414883903105",
            "732518796952",
            "141475743872",
            "703699428113",
            "215293407406",
            "239799778100",
            "597272584058",
            "308650936839",
            "496772302827",
            "737285907098",
            "205215134135",
            "185173466373",
            "824612777684",
            "714461704344",
            "710792515638",
            "554748871260",
            "010191308681",
            "293403483711",
            "186909932694",
            "976140232250",
            "639410496711",
            "882672047193",
            "812435417585",
            "891421728577",
            "945655997717",
            "279755637586",
            "176460930876",
            "606387030695",
            "354500841756",
            "145011527612",
            "400995596661",
            "018233690087",
            "102697596362",
            "232920776279",
            "218992288363",
            "817505688062",
            "245773576846",
            "682614356377",
            "588358899315",
            "414322272778",
            "585181422900",
            "508758539058",
            "610246004627",
            "093338326870",
            "217571151434",
            "247703378442",
            "652933227824",
            "650799724357",
            "259424632699",
            "876833762729",
            "698757614405",
            "523643710622",
            "853857329894",
            "832845249982",
            "870809490169",
            "012457198115",
            "375176912802",
            "665715337778",
            "720449525872",
            "874842642516",
            "764731626226",
            "559613924794",
            "903627821769",
            "968489839871",
            "053455910417",
            "895939516319",
            "616259715562",
            "039749450688",
            "918975596115",
            "124316773812",
            "026645747942",
            "793465803626",
            "903199442486",
            "441463116431",
            "280339010012",
            "229954959392",
            "587335202296",
            "555629363043",
            "101343137183",
            "891506071588",
            "815421126953",
            "783198935617",
            "401115777305",
            "970726860953",
            "304380488217",
            "423414740147",
            "141030991958",
            "878891236415",
            "710272859965",
            "646503623265",
            "285308196588",
            "176340768870",
            "839552274847",
            "887495471968",
            "996202595959",
        );
        $created = array("1436652000000",
            "1420063200000",
            "1420063200000",
            "1420063200000",
            "1426024800000",
            "1438812000000",
            "1426024800000",
            "1423951200000",
            "1436652000000",
            "1426024800000",
            "1436652000000",
            "1436652000000",
            "1435010400000",
            "1426024800000",
            "1438812000000",
            "1420063200000",
            "1438812000000",
            "1442354400000",
            "1436652000000",
            "1442354400000",
            "1438812000000",
            "1442354400000",
            "1423864800000",
            "1438812000000",
            "1436652000000",
            "1438812000000",
            "1420063200000",
            "1436652000000",
            "1420063200000",
            "1442354400000",
            "1442354400000",
            "1423864800000",
            "1435010400000",
            "1429567200000",
            "1420063200000",
            "1436652000000",
            "1420063200000",
            "1436652000000",
            "1438812000000",
            "1436652000000",
            "1429567200000",
            "1426024800000",
            "1426024800000",
            "1442354400000",
            "1436652000000",
            "1436652000000",
            "1426024800000",
            "1420063200000",
            "1438812000000",
            "1429567200000",
            "1436652000000",
            "1423951200000",
            "1423951200000",
            "1423864800000",
            "1442354400000",
            "1442354400000",
            "1436652000000",
            "1423864800000",
            "1436652000000",
            "1426024800000",
            "1426024800000",
            "1423864800000",
            "1436652000000",
            "1436652000000",
            "1436652000000",
            "1436652000000",
            "1426024800000",
            "1426024800000",
            "1436652000000",
            "1438812000000",
            "1436652000000",
            "1436652000000",
            "1436652000000",
            "1436652000000",
            "1442354400000",
            "1436652000000",
            "1436652000000",
            "1442354400000",
            "1420063200000",
            "1438812000000",
            "1426024800000",
            "1429567200000",
            "1420063200000",
            "1429567200000",
            "1436652000000",
            "1438812000000",
            "1438812000000",
            "1438812000000",
            "1442354400000",
            "1423951200000",
            "1426024800000",
            "1423951200000",
            "1442354400000",
            "1420063200000",
            "1436652000000",
            "1429567200000",
            "1423864800000",
            "1423951200000",
            "1436652000000",
            "1429567200000",
            "1426024800000",
            "1436652000000",
            "1436652000000",
            "1420063200000",
            "1435010400000",
            "1426024800000",
            "1436652000000",
            "1436652000000",
            "1420063200000",
            "1436652000000",
            "1420063200000",
            "1442354400000",
            "1436652000000",
            "1423951200000",
            "1436652000000",
            "1429567200000",
            "1429567200000",
            "1436652000000",
            "1423951200000",
            "1436652000000",
            "1436652000000",
            "1442354400000",
            "1420063200000",
            "1442354400000",
            "1435010400000",
            "1420063200000",
            "1426024800000",
            "1426024800000",
            "1426024800000",
            "1438812000000",
            "1436652000000",
            "1438812000000",
            "1436652000000",
            "1438812000000",
            "1420063200000",
            "1436652000000",
            "1429567200000",
            "1420063200000",
            "1426024800000",
            "1429567200000",
            "1436652000000",
            "1436652000000",
            "1420063200000",
            "1436652000000",
            "1436652000000",
            "1442354400000",
            "1426024800000",
            "1435010400000",
            "1420063200000",
            "1438812000000",
            "1438812000000",
            "1420063200000",
            "1436652000000",
            "1426024800000",
            "1429567200000",
            "1442354400000",
            "1436652000000",
            "1435010400000",
            "1435010400000",
            "1420063200000",
            "1423951200000",
            "1436652000000",
            "1420063200000",
            "1436652000000",
            "1435010400000",
            "1423951200000",
            "1423951200000",
            "1436652000000",
            "1426024800000",
            "1426024800000",
            "1436652000000",
            "1420063200000",
            "1420063200000",
            "1442354400000",
            "1436652000000",
            "1423951200000",
            "1436652000000",
            "1436652000000",
            "1436652000000",
            "1442354400000",
            "1436652000000",
            "1438812000000",
            "1442354400000",
            "1436652000000",
            "1438812000000",
            "1438812000000",
            "1438812000000",
            "1426024800000",
            "1436652000000",
            "1420063200000",
            "1435010400000",
            "1438812000000",
            "1438812000000",
            "1436652000000",
            "1442354400000",
            "1423951200000",
            "1442354400000",
            "1436652000000",
            "1429567200000",
            "1435010400000",
            "1435010400000",
            "1442354400000",
            "1436652000000",
            "1436652000000",
            "1423864800000",
            "1426024800000",
            "1436652000000",
            "1426024800000",
            "1436652000000",
            "1423951200000",
            "1429567200000",
            "1426024800000",
            "1436652000000",
            "1436652000000",
            "1436652000000",
            "1426024800000",
            "1426024800000",
            "1423864800000",
            "1420063200000",
            "1420063200000",
            "1438812000000",
            "1436652000000",
            "1436652000000",
            "1420063200000",
            "1436652000000",
            "1435010400000",
            "1429567200000",
            "1423951200000",
            "1436652000000",
            "1436652000000",
            "1436652000000",
            "1436652000000",
            "1438812000000",
            "1442354400000",
            "1429567200000",
            "1420063200000",
            "1423951200000",
            "1423951200000",
            "1426024800000",
            "1438812000000",
            "1420063200000",
            "1423951200000",
            "1426024800000",
            "1429567200000",
            "1435010400000",
            "1426024800000",
            "1426024800000",
            "1435010400000",
            "1435010400000",
            "1423864800000",
            "1423864800000",
            "1420063200000",
            "1420063200000",
            "1436652000000",
            "1436652000000",
            "1436652000000",
            "1423864800000",
            "1436652000000",
            "1436652000000",
            "1420063200000",
            "1420063200000",
            "1442354400000",
            "1426024800000",
            "1436652000000",
            "1423951200000",
            "1435010400000",
            "1420063200000",
            "1436652000000",
            "1426024800000",
            "1442354400000",
            "1426024800000",
            "1423951200000",
            "1438812000000",
            "1426024800000",
            "1435010400000",
            "1420063200000",
            "1438812000000",
            "1438812000000",
            "1435010400000",
            "1442354400000",
            "1423951200000",
            "1423951200000",
            "1436652000000",
            "1423951200000",
            "1438812000000",
            "1436652000000",
            "1438812000000",
            "1436652000000",
            "1423951200000",
            "1442354400000",
            "1442354400000",
            "1436652000000",
            "1436652000000",
            "1442354400000",
            "1436652000000",
            "1436652000000",
            "1429567200000",
            "1436652000000",
            "1429567200000",
            "1442354400000",
            "1436652000000",
            "1426024800000",
            "1436652000000",
            "1436652000000",
            "1426024800000",
            "1436652000000",
            "1426024800000",
            "1442354400000",
            "1429567200000",
            "1442354400000",
            "1423864800000",
            "1423864800000",
            "1436652000000",
            "1426024800000",
            "1436652000000",
            "1436652000000",
            "1435010400000",
            "1436652000000",
            "1420063200000",
            "1426024800000",
            "1438812000000",
            "1423864800000",
            "1442354400000",
            "1423864800000",
            "1423864800000",
            "1423951200000",
            "1420063200000",
            "1435010400000",
            "1429567200000",
            "1420063200000",
            "1423951200000",
            "1426024800000",
            "1429567200000",
            "1438812000000",
            "1438812000000",
            "1423951200000",
            "1438812000000",
            "1435010400000",
            "1436652000000",
            "1438812000000",
            "1442354400000",
            "1426024800000",
            "1423951200000",
            "1436652000000",
            "1420063200000",
            "1436652000000",
            "1423951200000",
            "1436652000000",
            "1435010400000",
            "1429567200000",
            "1426024800000",
            "1436652000000",
            "1420063200000",
            "1436652000000",
            "1426024800000",
            "1426024800000",
            "1429567200000",
            "1436652000000",
            "1436652000000",
            "1438812000000",
            "1429567200000",
            "1426024800000",
            "1426024800000",
            "1426024800000",
            "1438812000000",
            "1442354400000",
            "1429567200000",
            "1436652000000",
            "1438812000000",
            "1426024800000",
            "1423951200000",
            "1438812000000",
            "1420063200000",
            "1436652000000",
            "1442354400000",
            "1436652000000",
            "1436652000000",
            "1438812000000",
            "1438812000000",
            "1438812000000",
            "1436652000000",
            "1423951200000",
            "1442354400000",
            "1423951200000",
            "1436652000000",
            "1442354400000",
            "1438812000000",
            "1426024800000",
            "1420063200000",
            "1423951200000",
            "1436652000000",
            "1436652000000",
            "1442354400000",
            "1423951200000",
            "1438812000000",
            "1426024800000",
            "1429567200000",
            "1426024800000",
            "1423951200000",
            "1423951200000",
            "1423951200000",
            "1442354400000",
            "1426024800000",
            "1436652000000",
            "1435010400000",
            "1436652000000",
            "1436652000000",
            "1426024800000",
            "1436652000000",
            "1426024800000",
            "1442354400000",
            "1438812000000",
            "1442354400000",
            "1426024800000",
            "1436652000000",
            "1442354400000",
            "1426024800000",
            "1436652000000",
            "1420063200000",
            "1423951200000",
            "1438812000000",
            "1436652000000",
            "1438812000000",
            "1435010400000",
            "1429567200000",
            "1436652000000",
            "1438812000000",
            "1442354400000",
            "1423951200000",
            "1436652000000",
            "1435010400000",
            "1436652000000",
            "1436652000000",
            "1420063200000",
            "1420063200000",
            "1438812000000",
            "1423864800000",
            "1420063200000",
            "1426024800000",
            "1426024800000",
            "1426024800000",
            "1429567200000",
            "1426024800000",
            "1436652000000",
            "1420063200000",
            "1438812000000",
            "1435010400000",
            "1436652000000",
            "1442354400000",
            "1435010400000",
            "1426024800000",
            "1429567200000",
            "1435010400000",
            "1423951200000",
            "1438812000000",
            "1423864800000",
            "1429567200000",
            "1426024800000",
            "1436652000000",
            "1442354400000",
            "1436652000000",
            "1436652000000",
            "1438812000000",
            "1438812000000",
            "1435010400000",
            "1423864800000",
            "1442354400000",
            "1436652000000",
            "1426024800000",
            "1438812000000",
            "1438812000000",
            "1436652000000",
            "1442354400000",
            "1429567200000",
            "1436652000000",
            "1426024800000",
            "1436652000000",
            "1436652000000",
            "1442354400000",
            "1436652000000",
            "1436652000000",
            "1423864800000",
            "1423951200000",
            "1420063200000",
            "1426024800000",
            "1423951200000",
            "1436652000000",
            "1436652000000",
            "1436652000000",
            "1436652000000",
            "1436652000000",
            "1435010400000",
            "1436652000000",
            "1426024800000",
            "1426024800000",
            "1423864800000",
            "1438812000000",
            "1426024800000",
            "1426024800000",
            "1426024800000",);

        $ids = array("NUVIGJPPT0V",
            "F0JPOXRN9PO",
            "DQX7LUYFLB9",
            "27U7YOAH191",
            "LY6EH9R313W",
            "HYC2G4OWHCJ",
            "E9VKUP0YN4J",
            "0XBXZA83HZS",
            "H9ESXEOCTCY",
            "FOPS8K6VXHP",
            "8SC3778IMDV",
            "EEVIBJ00MCD",
            "T7LTT3834HL",
            "6COF28BY805",
            "Q1IJFL1PLF5",
            "O64JPFQU36M",
            "EOE1NUOM166",
            "HHHDWF3IHCO",
            "24OFG5PWTYV",
            "439F5O12UFM",
            "45GK9Q7QLQ5",
            "O0T35K2KD24",
            "8OWT3BDKWYO",
            "0SC60CNLZJ7",
            "PYOEA4M1X1N",
            "IZUVOQZ0CQF",
            "HKG6WKALQ5B",
            "X7G8Q8OUZ9J",
            "C9BH6K6LMC2",
            "12ZE3OL3N7E",
            "X0I7P1E1QJF",
            "VS5WOI8P855",
            "YGXSNE4EWF3",
            "4ITVDGIS28Y",
            "SH8LVZ2Y4GY",
            "LYWQSQ582BG",
            "985EO1K1UZS",
            "HIW4NBD8EVV",
            "CM3A78JOY9V",
            "5XL9E8Q8E79",
            "MS6DSFLNWS7",
            "10OKI0E33DE",
            "JR01Y4216NZ",
            "725MN9DVR04",
            "7SPQVAA548G",
            "J30U1ZVTMPH",
            "437KS8VT7GR",
            "FL9BKZY2ZUG",
            "JZBU80TTKCO",
            "DWUWFY0827T",
            "0MNXXN8NC28",
            "WCVO02RPDNG",
            "NF9BIYIUG2A",
            "9H3CGKRCT1C",
            "DLCKNMZ84HM",
            "DN3R9IOMXLN",
            "OES7O7YXP32",
            "8UWMNJWSS3K",
            "EDBX46YVWAK",
            "SMF1T37ZREY",
            "27KAGYCHEYG",
            "XGGIROGW1D5",
            "W7ULM3SJKGJ",
            "TTU8R9G44Y3",
            "YG134LKRIN1",
            "HYR8HQHD1NW",
            "EGRIYWYFJ8J",
            "LNRRUC1TW36",
            "DWCZ07NI0IH",
            "FJQLDN401UM",
            "2U6A4VAGJYV",
            "32VRFB90I6Z",
            "2EYY3L0UH0X",
            "8V831WVREUW",
            "JHBXJAQO8WY",
            "42JOKMZF7QU",
            "ZE1UA4DLTSM",
            "IQ15H2P8N5P",
            "7NSS9UV9O6Z",
            "416FMTDFYNR",
            "TQ09OV92JJN",
            "WIE2MP0U632",
            "W6KHDTI4G30",
            "5NDWX95FUYJ",
            "5927UYSJGJR",
            "J662UIEBD65",
            "WB7MBMBNUWI",
            "A9A9NUVUF0D",
            "7XQ0C6EQTLO",
            "7Y4I7W4BOQG",
            "8OL8JKPHFW8",
            "IJ826IMPWGT",
            "VJM5VB2S1QG",
            "EVWV0S43U4C",
            "88MIH9L651W",
            "1UGO9PTL7QU",
            "QL67CZ10WXJ",
            "RO3D3E8M3HX",
            "2BK23QFM3RB",
            "GIQKI65MNY0",
            "AI21YVYTNND",
            "TSHVNCF4OM0",
            "KSJL1O2G9JG",
            "1CU2QWMR8BG",
            "111YS9OFIIP",
            "ZY10QFY15T6",
            "29N70AFB4DZ",
            "3TSZ6P1L7F6",
            "583FH1ZU5ST",
            "JWSLRVLSAM2",
            "9NBWMUJXN37",
            "RRDKHRR7SDY",
            "LWQVAMNI5N3",
            "FDMEA9RHVLL",
            "SPWF14OM3LM",
            "LG3IZWZ0IQD",
            "UAI60M0HCJD",
            "Y33JZLUGRPN",
            "PV1QVOC5K2O",
            "3VOJ12ACSZB",
            "PXME6XNIJRE",
            "YLOKAZU33UD",
            "UWWVPBRX9ZV",
            "QX0ZSMNG929",
            "YFVGXCQOYOV",
            "CGWKW4B16HE",
            "1U5GZZMSLV5",
            "R8XYAYRW0DE",
            "TF1YTC2IC36",
            "EFYAIG2NWJ8",
            "D086WZNEU5H",
            "BJMY9TH96Y4",
            "NL6J6XH6QVR",
            "ZVL1BO0553F",
            "4IV5G5PZHXF",
            "GPNPMZ22CRG",
            "HC1MRFUIS6I",
            "WVW5M2KTSY8",
            "FIC85XTZ93B",
            "QTL8TMOKG4X",
            "YLR63YTJIKD",
            "HNCU8WODYO4",
            "BX9BZZB1UEL",
            "S7NTBJWM76F",
            "8KY1H0T04E8",
            "AA27Y8F7RAE",
            "FRC09QQ8PIB",
            "YG2U0UBO5E3",
            "DN2452BG2GZ",
            "8T7AZWNUPD1",
            "A3W15DOVFNZ",
            "2O10GMNBAWI",
            "QU0F3GMKQM8",
            "JTMF7RFYJ4L",
            "S3XSHG201EB",
            "YFVKEJQ9CHI",
            "FFLAFL2O8QP",
            "8FP90LWWZQ9",
            "X0GSEMD45LI",
            "ZZHYWLRZ3MG",
            "434AMSXFT3M",
            "DPI8VX7V4RN",
            "ABDLZ5DFZI8",
            "4NJVO80ZC8Q",
            "UGV78B8YNO0",
            "USN6CWIPG3K",
            "9HCPASS86N1",
            "OJNROJFANOY",
            "8I0Z4VB4VO9",
            "6W3TPKJY9I4",
            "F0VG9XQTWWE",
            "55A3J4W81LA",
            "9EYP2UOL3CC",
            "PSFN04UJLSR",
            "S0BF07HBF1Y",
            "A4Q4ALSZ3CT",
            "TX4ZPQV90OL",
            "RBGCTWMEGDS",
            "AD0J3Q5DTD8",
            "FGCNTN835O5",
            "V45LVKKWO7O",
            "0UJVF5NSH8A",
            "S9R9OE3YQDZ",
            "LN43P4876LB",
            "GD9WHHNIAFG",
            "V6GC62TX55C",
            "GYBRMK4IHZ5",
            "94J2VBXW3JC",
            "X3O9BXNXIO7",
            "P9Y01DHI45Y",
            "RWCT47RQTGI",
            "ACCFSA33OFL",
            "ME382ZDROFG",
            "GU9WKTQG9WQ",
            "J55CP7NPT9G",
            "SH4TBQ47EP2",
            "A790QQQJ36M",
            "RX5XOW926W9",
            "AO3KBXBAWK4",
            "J6YHH6GJLSM",
            "OAF07TE0O3U",
            "QHCFY1TAXRR",
            "H1O8RCXXOT7",
            "DB29ST4WCN2",
            "EBP35ABTJ5F",
            "NNIFDQW0PL8",
            "YWV3A83P4N0",
            "54AV5JXFS17",
            "F42WTWTJSU4",
            "HS1TCWZUDST",
            "CWKOTE5F48E",
            "9TAT09DZZXM",
            "ZEVNK4EARN5",
            "L9UFQA952EN",
            "02UGJ5TSL16",
            "2RZBLC25TKG",
            "GVBUKBDKNFM",
            "T3OP5K3RH9U",
            "AZV6BPEUNUM",
            "ZUXEFKNX07Y",
            "73P0ZO8BB2L",
            "5UZDC7KO4JS",
            "11LQQFMVOZO",
            "TXVP0IBQ505",
            "N1PPH8WBK64",
            "1UCC3K8OQW2",
            "XNM7VEFH3W6",
            "9RS1CZOUX3M",
            "11TNF63H9I0",
            "TFZL8586QG6",
            "WYSZMASVAKR",
            "378HZ2ZRYD0",
            "4HLQG2VX700",
            "2HX7HPKNGE8",
            "1N95MYRD2EG",
            "TU3MSG0H7NE",
            "8K1A7FGHGVU",
            "R9WLGZPA0T5",
            "LFZ480D0W7M",
            "NYCIBUSF9P0",
            "6CK42RQHFIP",
            "D7XSJ1MUUPI",
            "WLGOAPFCU7G",
            "UC5W7IHIZO3",
            "W0XQJKJJD9H",
            "F90KMT7VI3M",
            "WVCTQXBZR6O",
            "ZMMJ1ZQ5NPL",
            "8190YMV5E5U",
            "KSYEM61DK9U",
            "V2DMPU4N70J",
            "N6301XFWBWP",
            "RE0VFLBT0FH",
            "0A6V32LYIAV",
            "18ECNNNA72A",
            "NIURPWI0TLP",
            "LM5CKIQ9OR7",
            "XEYP5AU7JRT",
            "7BBTY5K0GNZ",
            "I2TRSF00U8U",
            "KEV0KDJV3JZ",
            "0CYCQIID69W",
            "QZLCS7E69S1",
            "UFTDRI84L7L",
            "TGJXMQ8Q3WL",
            "4NC76M30PV3",
            "LD5RS9RDW0J",
            "1X1CPO4QPC6",
            "JTGRNFQ2VMZ",
            "TGS02RXEY8O",
            "XD0FC1181D6",
            "MVH07YW01UZ",
            "HO95Q2I09J9",
            "3EHZXBBQJXE",
            "GT3L5F1WR3X",
            "6FYU6LQ3BS6",
            "RHLLZRT14ZH",
            "7B2BYBCO3FF",
            "PR7AAQB4C9O",
            "9ATK4DLX5GZ",
            "66VHEEFMO5F",
            "5Y6DN880AQG",
            "BHA2V61YD0W",
            "C5AFYP81W9P",
            "ZGYB4QO7H1M",
            "QC9LXJD4VHW",
            "OLMATNBLX6L",
            "3NX5JKXO7NA",
            "LGJVWTVNYEY",
            "HD273P6NQ7Z",
            "VR14J0DS4QQ",
            "0DFFRW0H4LG",
            "5FMOHAVJVZW",
            "35V8X3CESRF",
            "6QB4AGC4JT1",
            "XFVV92Q4O4X",
            "L62RXKI1PAP",
            "FPCC0EJP7I5",
            "U5WRRJ6KPJ5",
            "4PMOR3CPH9U",
            "CIS6Q796NQJ",
            "JLGUFXJL6L7",
            "1ZKG6IB29ST",
            "BEWZO8ZKQZ2",
            "3JHMS4TWSR1",
            "SQOWEJDG41E",
            "SORL14R0HA4",
            "A9OX5JRVN9U",
            "MKNDK4DQRB3",
            "7ZJ3CSB0W3O",
            "2HTZ65YCB96",
            "PG21Y78NFMW",
            "4S9BT21QFQJ",
            "UM3E8TMXWSJ",
            "IPXTKD3DWVE",
            "Z1GXMIQT77W",
            "89H1S6T0MET",
            "XUL9S1Q1LET",
            "T0TC9I5R6JT",
            "SQYZOCPXFOD",
            "OEYSVNNYKYO",
            "P71R9SNNTR2",
            "A94QA4MOSLD",
            "C56177LNS6J",
            "GQV2NVT6EYX",
            "TK2G3XSVL3Y",
            "XOB7SGJ0SZZ",
            "5YVBQIOA0YG",
            "GLA0N20MNIB",
            "220K08SOQCB",
            "MM5KG0NHKTV",
            "KQB89X63M06",
            "9GRDI3NK956",
            "990MKJ3ZAP5",
            "GOMO13LKV3B",
            "NY5NU6B7FEZ",
            "WD8E1ANSWW7",
            "23YWCVGULI1",
            "NNCIGT4FDQZ",
            "LATKR3E2W3R",
            "RK5IMRXFCZD",
            "FWD5BLT4IKT",
            "SKU2KUEHJ6P",
            "IB3PUIE83TV",
            "IQIO1GXVKV0",
            "JNDT2AJSDCA",
            "V9AUWWQCFC0",
            "RZN90NOTSDH",
            "WPLU01SPT46",
            "JCB4EBM3T6E",
            "1QTVZ5AE9WI",
            "5GK7H4OEHE2",
            "O491ZSG8MD5",
            "6IN4MA6LPK8",
            "KZPQOIXKF9A",
            "CIF6139QWUE",
            "J3UQK2O3XR7",
            "MMRU3EMIUSI",
            "CTQNVC411O2",
            "6CQXM0RXAX1",
            "HI1YPISJH33",
            "45ZIH7W4ARZ",
            "L61K0NFY6ES",
            "S9QM30OMN8C",
            "GKI14FBV4PB",
            "FWMX71AJQOA",
            "79WW3U6FA6Y",
            "LNKW5HCQ1U8",
            "OXHJIQ52YNC",
            "YEBPKLZBRJE",
            "COA89UOWVVZ",
            "XGX23N23PZG",
            "X0BFVZDJQMU",
            "ZU9MM2241V4",
            "DJ27O5L6PBO",
            "C1L96VYUZ78",
            "XU65ROCMTCZ",
            "W3HZPWZ8M9Z",
            "WXDUJ1ENPFC",
            "J6FE4L8RBHB",
            "G5AF65GL659",
            "1T5JMYOT19A",
            "3UENKXFVUGO",
            "ZC281X47P52",
            "1PAKHDTMSOG",
            "10RH8RSIVND",
            "XG6N5MDRT00",
            "RWF1I37YTBV",
            "3KHR2UX6CVE",
            "9XKKNP5S2ZO",
            "MZN1T8JK5Z1",
            "LNAQ946ZU7Z",
            "0ENH4QMUD0E",
            "JUP1Q5KU7WW",
            "35RUDNZEKLE",
            "DON0Q04495U",
            "ZAU4IUE5JVU",
            "FYLC083W244",
            "9OTWAZ39TGP",
            "56ATY1JXJ34",
            "TVF5D0C7VCO",
            "P3NE6KIZGL8",
            "V9LCQ36EKLQ",
            "DBM9PFSYGFQ",
            "8L9GZQQTOB7",
            "07JO5T7A29H",
            "IT4LKUYO8M3",
            "O5WD29PU7CC",
            "U5Q55T4E4GK",
            "YCGJHXCSJGR",
            "DIFQIVXJPCY",
            "ZUUUVBY620J",
            "LFVLSAV4A1A",
            "H2ZJYCDPLGO",
            "PMPMDPNEOL3",
            "058AEY4HK74",
            "MCZP80KR68Y",
            "VKGAHBUE3DG",
            "DQAFR1E4S41",
            "09M1BCB2U6Q",
            "X3FV46PTN5P",
            "AEUDWL95VUV",
            "BZZK0ER981B",
            "XCELASEYS6Q",
            "SEX71NXH9C2",
            "UFDWF9FK3XU",
            "R3KM61KD2XJ",
            "MRVIM7ZJGNR",
            "JL3O0RK3L9F",
            "2A8BP68252O",
            "YDLOJWNXUE3",
            "IS75Q0UF2CZ",
            "8KWFB664PZ2",
            "6OKK1A1QZU3",
            "506CPKBU7XQ",
            "LBSKXG5SG6T",
            "E7XKIC1E17G",
            "2RMO7T04HOV",
            "K8JAXVEO6RO",
            "3B12H1SE3N6",
            "QM8W13LD8NM",
            "OHHUT52F32A",
            "LZVEL6D0O2S",
            "J6AP9ZZSM5F",
            "5W7SIIWHOIX",
            "KA0XI853IKM",
            "WDOW6N7KUNY",
            "1PQWBPWNNL8",
            "7BGR6A2JV4Q",
            "XRAS7Z5LMQ3",
            "5A664YQUSFV",
            "EAXXMP6YS84",
            "LOAPRET1SV5",
            "4QBUUWKYFH4",
            "K44CFFX6JSZ",
            "FKU4C1GBS7P",
            "RX2VHHV8SI2",
            "V0RORZMZ23I",
            "KH1IL5340LD",
            "WUFFPZP6AU5",
            "WD0CZ3RZSA3",
            "PCHCFU52C8Q",
            "7X7O297N4VQ",
            "ZND4OKXMW8C",
            "MBYZ2F72R4E",
            "K2TWG15TU6U",
            "ZNCYNI4IEF8",
            "IAIMQF3S0U0",
            "ZC8WN6KAOJL",
            "ZGGBYV3TDN4",
            "QR9UL81WN8T",
            "JEEQ3WJVZDM",
            "SWIU1C1Q3CP",
            "QAO0X79FAQC",
            "1G69ES852WI",
            "ZJGQOLVY0WC",
            "V0S5U52UXB4",
            "XHXFT8XTCU1",
            "RZWFHPN7S4T",
            "821V544PFBM",
            "HE4CK6S8NF5",
            "IAHQUWILJVK",
            "ASSIKRRNIGZ",
            "JJEXYK61FQM",
            "SS17FYAFTO8",
            "2I6DEXQH7FE",
            "B2XI0KWDV9K",
            "8XF5HRUXJJ2",
            "7F96EBRU8Y7",
            "MF5IWH1IIYQ",
            "T6MTQZNZY1L",
            "AGO6L96DF25",
            "AASDXX1NPTY",
            "3Y4XH6ME5KA",
            "I8ZV9R54J8E",
            "2H2B1DB59SB",
            "0FNI59X8ASW",
            "271CAQ4U2OR",
            "SBLZXK00N49",
            "40JLUV08KH3",);

        $i = 0;
        foreach ($names as $firstname => $lasname) {
            $user = new Users();
            $user->setCreatedAt($created[$i]);
            $user->setDateOfBirth('645919200000');
            $user->setFirstName($firstname);
            $user->setLastName($lasname);
            $user->setGcmRegid('ff');
            $user->setGender($i % 2);
            $user->setIdNumber($ids[$i]);
            $user->setPhoneNumber($phones[$i++]);
            $user->setPincode('pincode');
            $user->setBranch($brnach);
            $user->setReferer($user1);
            $this->entity_manager->persist($user);

        }
        $this->entity_manager->flush();

    }

    private function referals()
    {
        $this->setEntityManager();
        $refs = array("NUVIGJPPT0V",
            "F0JPOXRN9PO",
            "DQX7LUYFLB9",
            "27U7YOAH191",
            "LY6EH9R313W",
            "HYC2G4OWHCJ",
            "E9VKUP0YN4J",
            "0XBXZA83HZS",
            "H9ESXEOCTCY",
            "FOPS8K6VXHP",
            "8SC3778IMDV",
            "EEVIBJ00MCD",
            "T7LTT3834HL",
            "6COF28BY805",
            "Q1IJFL1PLF5",
            "O64JPFQU36M",
            "EOE1NUOM166",
            "HHHDWF3IHCO",
            "24OFG5PWTYV",
            "439F5O12UFM",
            "45GK9Q7QLQ5",
            "O0T35K2KD24",
            "8OWT3BDKWYO",
            "0SC60CNLZJ7",
            "PYOEA4M1X1N",
            "IZUVOQZ0CQF",
            "HKG6WKALQ5B",
            "X7G8Q8OUZ9J",
            "C9BH6K6LMC2",
            "12ZE3OL3N7E",
            "X0I7P1E1QJF",
            "VS5WOI8P855",
            "YGXSNE4EWF3",
            "4ITVDGIS28Y",
            "SH8LVZ2Y4GY",
            "LYWQSQ582BG",
            "985EO1K1UZS",
            "HIW4NBD8EVV",
            "CM3A78JOY9V",
            "5XL9E8Q8E79",
            "MS6DSFLNWS7",
            "10OKI0E33DE",
            "JR01Y4216NZ",
            "725MN9DVR04",
            "7SPQVAA548G",
            "J30U1ZVTMPH",
            "437KS8VT7GR",
            "FL9BKZY2ZUG",
            "JZBU80TTKCO",
            "DWUWFY0827T",
            "0MNXXN8NC28",
            "WCVO02RPDNG",
            "NF9BIYIUG2A",
            "9H3CGKRCT1C",
            "DLCKNMZ84HM",
            "DN3R9IOMXLN",
            "OES7O7YXP32",
            "8UWMNJWSS3K",
            "EDBX46YVWAK",
            "SMF1T37ZREY",
            "27KAGYCHEYG",
            "XGGIROGW1D5",
            "W7ULM3SJKGJ",
            "TTU8R9G44Y3",
            "YG134LKRIN1",
            "HYR8HQHD1NW",
            "EGRIYWYFJ8J",
            "LNRRUC1TW36",
            "DWCZ07NI0IH",
            "FJQLDN401UM",
            "2U6A4VAGJYV",
            "32VRFB90I6Z",
            "2EYY3L0UH0X",
            "8V831WVREUW",
            "JHBXJAQO8WY",
            "42JOKMZF7QU",
            "ZE1UA4DLTSM",
            "IQ15H2P8N5P",
            "7NSS9UV9O6Z",
            "416FMTDFYNR",
            "TQ09OV92JJN",
            "WIE2MP0U632",
            "W6KHDTI4G30",
            "5NDWX95FUYJ",
            "5927UYSJGJR",
            "J662UIEBD65",
            "WB7MBMBNUWI",
            "A9A9NUVUF0D",
            "7XQ0C6EQTLO",
            "7Y4I7W4BOQG",
            "8OL8JKPHFW8",
            "IJ826IMPWGT",
            "VJM5VB2S1QG",
            "EVWV0S43U4C",
            "88MIH9L651W",
            "1UGO9PTL7QU",
            "QL67CZ10WXJ",
            "RO3D3E8M3HX",
            "2BK23QFM3RB",
            "GIQKI65MNY0",
            "AI21YVYTNND",
            "TSHVNCF4OM0",
            "KSJL1O2G9JG",
            "1CU2QWMR8BG",
            "111YS9OFIIP",
            "ZY10QFY15T6",
            "29N70AFB4DZ",
            "3TSZ6P1L7F6",
            "583FH1ZU5ST",
            "JWSLRVLSAM2",
            "9NBWMUJXN37",
            "RRDKHRR7SDY",
            "LWQVAMNI5N3",
            "FDMEA9RHVLL",
            "SPWF14OM3LM",
            "LG3IZWZ0IQD",
            "UAI60M0HCJD",
            "Y33JZLUGRPN",
            "PV1QVOC5K2O",
            "3VOJ12ACSZB",
            "PXME6XNIJRE",
            "YLOKAZU33UD",
            "UWWVPBRX9ZV",
            "QX0ZSMNG929",
            "YFVGXCQOYOV",
            "CGWKW4B16HE",
            "1U5GZZMSLV5",
            "R8XYAYRW0DE",
            "TF1YTC2IC36",
            "EFYAIG2NWJ8",
            "D086WZNEU5H",
            "BJMY9TH96Y4",
            "NL6J6XH6QVR",
            "ZVL1BO0553F",
            "4IV5G5PZHXF",
            "GPNPMZ22CRG",
            "HC1MRFUIS6I",
            "WVW5M2KTSY8",
            "FIC85XTZ93B",
            "QTL8TMOKG4X",
            "YLR63YTJIKD",
            "HNCU8WODYO4",
            "BX9BZZB1UEL",
            "S7NTBJWM76F",
            "8KY1H0T04E8",
            "AA27Y8F7RAE",
            "FRC09QQ8PIB",
            "YG2U0UBO5E3",
            "DN2452BG2GZ",
            "8T7AZWNUPD1",
            "A3W15DOVFNZ",
            "2O10GMNBAWI",
            "QU0F3GMKQM8",
            "JTMF7RFYJ4L",
            "S3XSHG201EB",
            "YFVKEJQ9CHI",
            "FFLAFL2O8QP",
            "8FP90LWWZQ9",
            "X0GSEMD45LI",
            "ZZHYWLRZ3MG",
            "434AMSXFT3M",
            "DPI8VX7V4RN",
            "ABDLZ5DFZI8",
            "4NJVO80ZC8Q",
            "UGV78B8YNO0",
            "USN6CWIPG3K",
            "9HCPASS86N1",
            "OJNROJFANOY",
            "8I0Z4VB4VO9",
            "6W3TPKJY9I4",
            "F0VG9XQTWWE",
            "55A3J4W81LA",
            "9EYP2UOL3CC",
            "PSFN04UJLSR",
            "S0BF07HBF1Y",
            "A4Q4ALSZ3CT",
            "TX4ZPQV90OL",
            "RBGCTWMEGDS",
            "AD0J3Q5DTD8",
            "FGCNTN835O5",
            "V45LVKKWO7O",
            "0UJVF5NSH8A",
            "S9R9OE3YQDZ",
            "LN43P4876LB",
            "GD9WHHNIAFG",
            "V6GC62TX55C",
            "GYBRMK4IHZ5",
            "94J2VBXW3JC",
            "X3O9BXNXIO7",
            "P9Y01DHI45Y",
            "RWCT47RQTGI",
            "ACCFSA33OFL",
            "ME382ZDROFG",
            "GU9WKTQG9WQ",
            "J55CP7NPT9G",
            "SH4TBQ47EP2",
            "A790QQQJ36M",
            "RX5XOW926W9",
            "AO3KBXBAWK4",
            "J6YHH6GJLSM",
            "OAF07TE0O3U",
            "QHCFY1TAXRR",
            "H1O8RCXXOT7",
            "DB29ST4WCN2",
            "EBP35ABTJ5F",
            "NNIFDQW0PL8",
            "YWV3A83P4N0",
            "54AV5JXFS17",
            "F42WTWTJSU4",
            "HS1TCWZUDST",
            "CWKOTE5F48E",
            "9TAT09DZZXM",
            "ZEVNK4EARN5",
            "L9UFQA952EN",
            "02UGJ5TSL16",
            "2RZBLC25TKG",
            "GVBUKBDKNFM",
            "T3OP5K3RH9U",
            "AZV6BPEUNUM",
            "ZUXEFKNX07Y",
            "73P0ZO8BB2L",
            "5UZDC7KO4JS",
            "11LQQFMVOZO",
            "TXVP0IBQ505",
            "N1PPH8WBK64",
            "1UCC3K8OQW2",
            "XNM7VEFH3W6",
            "9RS1CZOUX3M",
            "11TNF63H9I0",
            "TFZL8586QG6",
            "WYSZMASVAKR",
            "378HZ2ZRYD0",
            "4HLQG2VX700",
            "2HX7HPKNGE8",
            "1N95MYRD2EG",
            "TU3MSG0H7NE",
            "8K1A7FGHGVU",
            "R9WLGZPA0T5",
            "LFZ480D0W7M",
            "NYCIBUSF9P0",
            "6CK42RQHFIP",
            "D7XSJ1MUUPI",
            "WLGOAPFCU7G",
            "UC5W7IHIZO3",
            "W0XQJKJJD9H",
            "F90KMT7VI3M",
            "WVCTQXBZR6O",
            "ZMMJ1ZQ5NPL",
            "8190YMV5E5U",
            "KSYEM61DK9U",
            "V2DMPU4N70J",
            "N6301XFWBWP",
            "RE0VFLBT0FH",
            "0A6V32LYIAV",
            "18ECNNNA72A",
            "NIURPWI0TLP",
            "LM5CKIQ9OR7",
            "XEYP5AU7JRT",
            "7BBTY5K0GNZ",
            "I2TRSF00U8U",
            "KEV0KDJV3JZ",
            "0CYCQIID69W",
            "QZLCS7E69S1",
            "UFTDRI84L7L",
            "TGJXMQ8Q3WL",
            "4NC76M30PV3",
            "LD5RS9RDW0J",
            "1X1CPO4QPC6",
            "JTGRNFQ2VMZ",
            "TGS02RXEY8O",
            "XD0FC1181D6",
            "MVH07YW01UZ",
            "HO95Q2I09J9",
            "3EHZXBBQJXE",
            "GT3L5F1WR3X",
            "6FYU6LQ3BS6",
            "RHLLZRT14ZH",
            "7B2BYBCO3FF",
            "PR7AAQB4C9O",
            "9ATK4DLX5GZ",
            "66VHEEFMO5F",
            "5Y6DN880AQG",
            "BHA2V61YD0W",
            "C5AFYP81W9P",
            "ZGYB4QO7H1M",
            "QC9LXJD4VHW",
            "OLMATNBLX6L",
            "3NX5JKXO7NA",
            "LGJVWTVNYEY",
            "HD273P6NQ7Z",
            "VR14J0DS4QQ",
            "0DFFRW0H4LG",
            "5FMOHAVJVZW",
            "35V8X3CESRF",
            "6QB4AGC4JT1",
            "XFVV92Q4O4X",
            "L62RXKI1PAP",
            "FPCC0EJP7I5",
            "U5WRRJ6KPJ5",
            "4PMOR3CPH9U",
            "CIS6Q796NQJ",
            "JLGUFXJL6L7",
            "1ZKG6IB29ST",
            "BEWZO8ZKQZ2",
            "3JHMS4TWSR1",
            "SQOWEJDG41E",
            "SORL14R0HA4",
            "A9OX5JRVN9U",
            "MKNDK4DQRB3",
            "7ZJ3CSB0W3O",
            "2HTZ65YCB96",
            "PG21Y78NFMW",
            "4S9BT21QFQJ",
            "UM3E8TMXWSJ",
            "IPXTKD3DWVE",
            "Z1GXMIQT77W",
            "89H1S6T0MET",
            "XUL9S1Q1LET",
            "T0TC9I5R6JT",
            "SQYZOCPXFOD",
            "OEYSVNNYKYO",
            "P71R9SNNTR2",
            "A94QA4MOSLD",
            "C56177LNS6J",
            "GQV2NVT6EYX",
            "TK2G3XSVL3Y",
            "XOB7SGJ0SZZ",
            "5YVBQIOA0YG",
            "GLA0N20MNIB",
            "220K08SOQCB",
            "MM5KG0NHKTV",
            "KQB89X63M06",
            "9GRDI3NK956",
            "990MKJ3ZAP5",
            "GOMO13LKV3B",
            "NY5NU6B7FEZ",
            "WD8E1ANSWW7",
            "23YWCVGULI1",
            "NNCIGT4FDQZ",
            "LATKR3E2W3R",
            "RK5IMRXFCZD",
            "FWD5BLT4IKT",
            "SKU2KUEHJ6P",
            "IB3PUIE83TV",
            "IQIO1GXVKV0",
            "JNDT2AJSDCA",
            "V9AUWWQCFC0",
            "RZN90NOTSDH",
            "WPLU01SPT46",
            "JCB4EBM3T6E",
            "1QTVZ5AE9WI",
            "5GK7H4OEHE2",
            "O491ZSG8MD5",
            "6IN4MA6LPK8",
            "KZPQOIXKF9A",
            "CIF6139QWUE",
            "J3UQK2O3XR7",
            "MMRU3EMIUSI",
            "CTQNVC411O2",
            "6CQXM0RXAX1",
            "HI1YPISJH33",
            "45ZIH7W4ARZ",
            "L61K0NFY6ES",
            "S9QM30OMN8C",
            "GKI14FBV4PB",
            "FWMX71AJQOA",
            "79WW3U6FA6Y",
            "LNKW5HCQ1U8",
            "OXHJIQ52YNC",
            "YEBPKLZBRJE",
            "COA89UOWVVZ",
            "XGX23N23PZG",
            "X0BFVZDJQMU",
            "ZU9MM2241V4",
            "DJ27O5L6PBO",
            "C1L96VYUZ78",
            "XU65ROCMTCZ",
            "W3HZPWZ8M9Z",
            "WXDUJ1ENPFC",
            "J6FE4L8RBHB",
            "G5AF65GL659",
            "1T5JMYOT19A",
            "3UENKXFVUGO",
            "ZC281X47P52",
            "1PAKHDTMSOG",
            "10RH8RSIVND",
            "XG6N5MDRT00",
            "RWF1I37YTBV",
            "3KHR2UX6CVE",
            "9XKKNP5S2ZO",
            "MZN1T8JK5Z1",
            "LNAQ946ZU7Z",
            "0ENH4QMUD0E",
            "JUP1Q5KU7WW",
            "35RUDNZEKLE",
            "DON0Q04495U",
            "ZAU4IUE5JVU",
            "FYLC083W244",
            "9OTWAZ39TGP",
            "56ATY1JXJ34",
            "TVF5D0C7VCO",
            "P3NE6KIZGL8",
            "V9LCQ36EKLQ",
            "DBM9PFSYGFQ",
            "8L9GZQQTOB7",
            "07JO5T7A29H",
            "IT4LKUYO8M3",
            "O5WD29PU7CC",
            "U5Q55T4E4GK",
            "YCGJHXCSJGR",
            "DIFQIVXJPCY",
            "ZUUUVBY620J",
            "LFVLSAV4A1A",
            "H2ZJYCDPLGO",
            "PMPMDPNEOL3",
            "058AEY4HK74",
            "MCZP80KR68Y",
            "VKGAHBUE3DG",
            "DQAFR1E4S41",
            "09M1BCB2U6Q",
            "X3FV46PTN5P",
            "AEUDWL95VUV",
            "BZZK0ER981B",
            "XCELASEYS6Q",
            "SEX71NXH9C2",
            "UFDWF9FK3XU",
            "R3KM61KD2XJ",
            "MRVIM7ZJGNR",
            "JL3O0RK3L9F",
            "2A8BP68252O",
            "YDLOJWNXUE3",
            "IS75Q0UF2CZ",
            "8KWFB664PZ2",
            "6OKK1A1QZU3",
            "506CPKBU7XQ",
            "LBSKXG5SG6T",
            "E7XKIC1E17G",
            "2RMO7T04HOV",
            "K8JAXVEO6RO",
            "3B12H1SE3N6",
            "QM8W13LD8NM",
            "OHHUT52F32A",
            "LZVEL6D0O2S",
            "J6AP9ZZSM5F",
            "5W7SIIWHOIX",
            "KA0XI853IKM",
            "WDOW6N7KUNY",
            "1PQWBPWNNL8",
            "7BGR6A2JV4Q",
            "XRAS7Z5LMQ3",
            "5A664YQUSFV",
            "EAXXMP6YS84",
            "LOAPRET1SV5",
            "4QBUUWKYFH4",
            "K44CFFX6JSZ",
            "FKU4C1GBS7P",
            "RX2VHHV8SI2",
            "V0RORZMZ23I",
            "KH1IL5340LD",
            "WUFFPZP6AU5",
            "WD0CZ3RZSA3",
            "PCHCFU52C8Q",
            "7X7O297N4VQ",
            "ZND4OKXMW8C",
            "MBYZ2F72R4E",
            "K2TWG15TU6U",
            "ZNCYNI4IEF8",
            "IAIMQF3S0U0",
            "ZC8WN6KAOJL",
            "ZGGBYV3TDN4",
            "QR9UL81WN8T",
            "JEEQ3WJVZDM",
            "SWIU1C1Q3CP",
            "QAO0X79FAQC",
            "1G69ES852WI",
            "ZJGQOLVY0WC",
            "V0S5U52UXB4",
            "XHXFT8XTCU1",
            "RZWFHPN7S4T",
            "821V544PFBM",
            "HE4CK6S8NF5",
            "IAHQUWILJVK",
            "ASSIKRRNIGZ",
            "JJEXYK61FQM",
            "SS17FYAFTO8",
            "2I6DEXQH7FE",
            "B2XI0KWDV9K",
            "8XF5HRUXJJ2",
            "7F96EBRU8Y7",
            "MF5IWH1IIYQ",
            "T6MTQZNZY1L",
            "AGO6L96DF25",
            "AASDXX1NPTY",
            "3Y4XH6ME5KA",
            "I8ZV9R54J8E",
            "2H2B1DB59SB",
            "0FNI59X8ASW",
            "271CAQ4U2OR",
            "SBLZXK00N49",
            "40JLUV08KH3",);
        foreach ($refs as $user_id) {


            $package_plan = $this->entity_manager->getRepository(Constants::ENTITY_PACKAGE_PLANS)->findOneById(rand(1,2));
            $user = $this->entity_manager->getRepository(Constants::ENTITY_USERS)->findOneByIdNumber($user_id);
           $package = new SubscribedPackages();
            $package->setDateActivated($user->getCreatedAt());
            $package->setUser($user);
            $package->setPackagePlan($package_plan);
            $this->entity_manager->persist($package);
        }
        $this->entity_manager->flush();
    }

    public function dependants()
    {
        $this->setEntityManager();
        $deps = array(            array("Charity"=>"Gooden"),
            array("Etha"=>"Shannon"),
            array("Ligia"=>"Torrez"),
            array("Hanna"=>"Butts"),
            array("Jamila"=>"Beverly"),
            array("Devin"=>"Slattery"),
            array("Jeanie"=>"Lebron"),
            array("Chong"=>"Peck"),
            array("Usha"=>"Daly"),
            array("Roxie"=>"Grey"),
            array("Marin"=>"Rider"),
            array("Carlee"=>"Shephard"),
            array("Clarice"=>"Reedy"),
            array("Kathrin"=>"Overstreet"),
            array("Jeromy"=>"Pride"),
            array("Troy"=>"Boss"),
            array("Phoebe"=>"Wallis"),
            array("Syble"=>"Mccrary"),
            array("Gaston"=>"Turnbull"),
            array("Demetrius"=>"Buckner"),
            array("Maynard"=>"Mcalister"),
            array("Jude"=>"Houghton"),
            array("Chaya"=>"Trevino"),
            array("Darius"=>"Sells"),
            array("Tanika"=>"Longoria"),
            array("Genoveva"=>"Prentice"),
            array("Pinkie"=>"Kimball"),
            array("Hilde"=>"Salter"),
            array("Emilee"=>"Jarvis"),
            array("Aleta"=>"Fielder"),
            array("Jamison"=>"Bond"),
            array("Chantel"=>"Brinkley"),
            array("Andrew"=>"Medeiros"),
            array("Elliott"=>"Marble"),
            array("Winona"=>"Diehl"),
            array("Tyron"=>"Wild"),
            array("Jannette"=>"Woodward"),
            array("Ty"=>"Pleasant"),
            array("Keven"=>"Irvin"),
            array("Phyliss"=>"Kurtz"),
            array("Felisha"=>"Tellez"),
            array("Juliette"=>"Begley"),
            array("Jasper"=>"Donahue"),
            array("Taren"=>"Elizondo"),
            array("Vada"=>"Dolan"),
            array("Bailey"=>"Allard"),
            array("Lissa"=>"Huskey"),
            array("Adelia"=>"Ratliff"),
            array("Reyna"=>"Najera"),
            array("Suzy"=>"Pickett"),
            array("Fernando"=>"Wells"),
            array("Clark"=>"Morris"),
            array("Myrtle"=>"Bush"),
            array("Herbert"=>"Hansen"),
            array("Jody"=>"Berry"),
            array("Kathleen"=>"Gilbert"),
            array("Javier"=>"Gonzales"),
            array("Henry"=>"Chavez"),
            array("Heather"=>"George"),
            array("Kim"=>"Lynch"),
            array("Harry"=>"Gardner"),
            array("Yolanda"=>"May"),
            array("Carroll"=>"Jacobs"),
            array("Angie"=>"Townsend"),
            array("Jim"=>"Taylor"),
            array("Priscilla"=>"Armstrong"),
            array("Sidney"=>"Ruiz"),
            array("Violet"=>"Cortez"),
            array("Shirley"=>"Clark"),
            array("Jesse"=>"Butler"),
            array("Jordan"=>"Adkins"),
            array("Norma"=>"Daniels"),
            array("Jeremiah"=>"Lawrence"),
            array("Erika"=>"Lawson"),
            array("Clarence"=>"Walker"),
            array("Alberto"=>"Lambert"),
            array("Beatrice"=>"Moss"),
            array("Geoffrey"=>"Hayes"),
            array("Donna"=>"Reid"),
            array("Kellie"=>"Morton"),
            array("Deanna"=>"Rodgers"),
            array("Cary"=>"Nichols"),
            array("Brandy"=>"Cooper"),
            array("Cora"=>"Rivera"),
            array("Lynn"=>"Parks"),
            array("Jermaine"=>"Romero"),
            array("Guillermo"=>"Chambers"),
            array("Zachary"=>"Boone"),
            array("Tommie"=>"Pittman"),
            array("Alex"=>"Wise"),
            array("Guadalupe"=>"Guerrero"),
            array("Andrew"=>"Arnold"),
            array("Myra"=>"Luna"),
            array("Lucas"=>"Webster"),
            array("Alexander"=>"Bryant"),
            array("Rudolph"=>"Barrett"),
            array("Horace"=>"Higgins"),
            array("Dolores"=>"Valdez"),
            array("Sharon"=>"Ramos"),
            array("Sam"=>"Nash"),
            array("Gerardo"=>"Palmer"),
            array("Juanita"=>"Sandoval"),
            array("Grace"=>"Haynes"),
            array("Yvette"=>"Oliver"),
            array("Patty"=>"Moore"),
            array("Fredrick"=>"Casey"),
            array("Clay"=>"Chapman"),
            array("Rene"=>"Goodman"),
            array("Malcolm"=>"Dawson"),
            array("Ernest"=>"Diaz"),
            array("Jamie"=>"Rogers"),
            array("Damon"=>"Richardson"),
            array("Nellie"=>"Bradley"),
            array("Lora"=>"Castillo"),
            array("Patrick"=>"Boyd"),
            array("Tom"=>"Mason"),
            array("Elsie"=>"Carson"),
            array("Winston"=>"Bridges"),
            array("Blake"=>"Lamb"),
            array("Judith"=>"Fuller"),
            array("Erma"=>"Klein"),
            array("Randall"=>"Frazier"),
            array("Teri"=>"Allison"),
            array("Lorena"=>"Franklin"),
            array("Estelle"=>"Strickland"),
            array("Molly"=>"Rodriquez"),
            array("Jane"=>"Glover"),
            array("Tabitha"=>"Figueroa"),
            array("Amanda"=>"Coleman"),
            array("Bryan"=>"Jones"),
            array("Dale"=>"Mckinney"),
            array("Nelson"=>"Torres"),
            array("Antonia"=>"Cobb"),
            array("Renee"=>"Williamson"),
            array("Noah"=>"Ford"),
            array("Constance"=>"Russell"),
            array("Nettie"=>"Warren"),
            array("Mitchell"=>"Garrett"),
            array("Clara"=>"Barker"),
            array("Kim"=>"Lucas"),
            array("Kerry"=>"Wade"),
            array("Hattie"=>"Wood"),
            array("Jennie"=>"Hart"),
            array("Lynn"=>"Martin"),
            array("Maurice"=>"Elliott"),
            array("Johnathan"=>"Montgomery"),
            array("Anthony"=>"Henderson"),
            array("Dave"=>"Stone"),
            array("Denise"=>"Barton"),
            array("Jack"=>"Wagner"),);

        $parents= array("AGO6L96DF25",
            "4HLQG2VX700",
            "XFVV92Q4O4X",
            "BJMY9TH96Y4",
            "D086WZNEU5H",
            "AEUDWL95VUV",
            "3B12H1SE3N6",
            "H2ZJYCDPLGO",
            "EAXXMP6YS84",
            "DQX7LUYFLB9",
            "WYSZMASVAKR",
            "439F5O12UFM",
            "RO3D3E8M3HX",
            "MZN1T8JK5Z1",
            "LZVEL6D0O2S",
            "4NJVO80ZC8Q",
            "T0TC9I5R6JT",
            "MRVIM7ZJGNR",
            "V45LVKKWO7O",
            "9ATK4DLX5GZ",
            "1PQWBPWNNL8",
            "EOE1NUOM166",
            "DB29ST4WCN2",
            "IAHQUWILJVK",
            "WDOW6N7KUNY",
            "X3FV46PTN5P",
            "QTL8TMOKG4X",
            "KSYEM61DK9U",
            "6COF28BY805",
            "P71R9SNNTR2",);

        foreach ($parents as $user_id) {


die('test');
            $user = $this->entity_manager->getRepository(Constants::ENTITY_USERS)->findOneByIdNumber($user_id);
            $i=0;
            while($i<5)
            {
                $u = $deps[$i++];
                $id =3;
                if($i==1||$i==2)$id=4;
                $package_plan = $this->entity_manager->getRepository(Constants::ENTITY_PACKAGE_PLANS)->findOneById($id);
                foreach($u as $first_name=>$last_name)
                {
                    $r_type = $this->entity_manager->getRepository(Constants::ENTITY_USER_RELATION_TYPES)->findOneById($i);
                    $dependent =new UserDependents();
                    $dependent->setFirstName($first_name);
                    $dependent->setLastName($last_name);
                    $dependent->setGender(rand(0,1));
                    $dependent->setDateOfBirth('708300000000');
                    $dependent->setJoinedAt($user->getCreatedAt());
                    $dependent->setRelationType($r_type);
                    $dependent->setUser($user);
                    $this->entity_manager->persist($dependent);
                    $package = new SubscribedPackages();
                    $package->setDateActivated($user->getCreatedAt());
                    $package->setUser($user);
                    $package->setPackagePlan($package_plan);
                    $package->setIsDependent(true);
                    $package->setDependent($dependent);
                    $this->entity_manager->persist($package);
                    $this->entity_manager->flush();
                }
            }

        }
    }

    public function insertTest($request){
        $this->setEntityManager();
      //  $raw_results = $this->entity_manager->getRepository(Constants::ENTITY_FR)->findByPhoneNumber('+263783211562');

        //  $result.= '</results>';
        //  return $result;
        $result = '<results>';
        $user = $this->entity_manager->getRepository(Constants::ENTITY_USERS)->findOneByPhoneNumber('+263773212212');// "SELECT * FROM users WHERE phone_number = '".$phone_number."'";
        if ($user == null) {
            //no user
            $result.='<result>'. Constants::ON_FAILURE_CONST.'</result>';
            $result.= '</results>';
            return $result;
        }
        $checkBc = 1;
        if($checkBc == Constants::CHECK_BUSANDCASH_STATUS){
            $Bc_plan = $this->entity_manager->getRepository(Constants::ENTITY_PACKAGE_PLANS)->findOneById(Constants::BUS_AND_CASH_ADITIONAL_BEN);
            $query = $this->entity_manager->createQueryBuilder();
            $query->select(array('pack'))
                ->from('Application\Entity\SubscribedPackages', 'pack')
                ->where($query->expr()->orX(
                    $query->expr()->eq('pack.user', '?1')
                ))
                ->andWhere($query->expr()->orX(
                    $query->expr()->eq('pack.packagePlan', '?2')
                ))
                ->setParameters(array(1=> $user,2 =>$Bc_plan))
                ->orderBy('pack.id', 'DESC')
                ->setMaxResults(1);
            $query = $query->getQuery();
            $bc_data = $query->getResult();
            $BcActivatedAt = '';
            if ($bc_data != null) {
                echo 'heree';
                foreach ($bc_data as $bc_package) {
                    // $bc_package = new SubscribedPackages();
                    if($bc_package->getStatus()){

                        // $plan = $bc_package->getPackagePlan();
                        $BcActivatedAt = $bc_package->getDateActivated();
                        $BcActivatedAt = $BcActivatedAt->getTimestamp()*1000;
                        //active
                        $last_payment = $this->entity_manager->getRepository(Constants::ENTITY_USER_PAYMENTS)->findBy(array('subscribedPackage' => $bc_package), array('monthPaidFor' => 'ASC'), 1);
                        if ($last_payment != null) {
                            $result .= '<bcActivateddAt>' . $BcActivatedAt . '</bcActivateddAt>';
                            return $result;
                        }
                    }

                }

            }

        }
        die('here');
        if($raw_results != null){
            die('here');
            foreach($raw_results as $data) {
                //$raw_results = new UserActivitiesData();
              // $data = new FriendsPayments();
                $phone_number =  $data->getPhoneNumber();
              ///  $data->getFriendName();
                $timestamp = $data->getDateTime();
                $user_activity = new UserActivitiesData();
                $user = $this->entity_manager->getRepository(Constants::ENTITY_USERS)->findOneByPhoneNumber($phone_number);

                if ($user ==null) {
                    //user not found
                    $this->return_die(Constants::ON_FAILURE_CONST);
                }

                $newstring = str_replace(".","",$data->getFriendName());
                $user_activity->setMessage('You have paid '.$data->getAmountPaid().' for '.$newstring. '. Reference ID : '.$data->getRefNumber())
                    ->setTitle(self::SHIRI_FUNERAL_PLAN_PAYMENT)
                    ->setUser($user)
                    ->setDateTime($timestamp)
                    ->setMsgId(Constants::SERVER_MSG);
                $this->entity_manager->persist($user_activity);
                $this->entity_manager->flush();

            }
        }

//        $raw_results = $this->entity_manager->getRepository(Constants::ENTITY_FROM_NETTCASH_SERVER)->findAll();
//        if($raw_results != null){
//            foreach($raw_results as $user){
//                //$user = new FromNettcashServer();
//                $userData = $user->getUser();
//               // $userData = new Users();
//
//              // $data = new FriendsPayments();
//              ///  $data->getFriendName();
//                $timestamp = $user->getDatePaid();
//                $user_activity = new UserActivitiesData();
//                $str_amount = number_format((float)$user->getAmountPaid(), 2, '.', '');
//              //  $str_amount = round((int)$user->getAmountPaid(),2);
//                $user_activity->setMessage('You have paid $'.$str_amount.Constants::FOR_YOUR_POLICY_ACCOUNT)
//                    ->setTitle(self::SHIRI_FUNERAL_PLAN_PAYMENT)
//                    ->setUser($userData)
//                    ->setDateTime($timestamp)
//                    ->setMsgId(Constants::SERVER_MSG);
//                $this->entity_manager->persist($user_activity);
//                $this->entity_manager->flush();
//
//            }
//        }

    }

    public function insertTestEcocash(){
//        $amounts = array(array("+263773036911"=>"13"),
//            array("+263772739647"=>"13"),
//            array("+263774734754"=>"13"),
//            array("+263772136862"=>"13"),
//            array("+263773012055"=>"13"),
//            array("+263774133329"=>"13"),
//            array("+263776353022"=>"13"),
//            array("+263772929433"=>"13"),
//            array("+263776173976"=>"13"),
//            array("+263736930844"=>"13"),
//            array("+263772893150"=>"13"),
//            array("+263773232761"=>"13"),
//            array("+263782489690"=>"13"),
//            array("+263772425633"=>"13"),
//            array("+263778436147"=>"13"),
//            array("+263772581472"=>"19"),
//            array("+263775797614"=>"13"),
//            array("+263717069754"=>"13"),
//            array("+263773424220"=>"13"),
//            array("+263778915938"=>"13"),
//            array("+263733217128"=>"13"),
//            array("+263712333159"=>"13"),
//            array("+263772257979"=>"13"),
//            array("+263773261153"=>"13"),
//            array("+263773476226"=>"13"),
//            array("+263773476226"=>"13"),
//            array("+263774740084"=>"13"),
//            array("+263712325352"=>"13"),
//            array("+263778071305"=>"13"),
//            array("+263775774573"=>"16"),
//            array("+263775208153"=>"13"),
//            array("+263775184380"=>"20"),
//            array("+263774259024"=>"13"),
//            array("+263772408312"=>"13"),
//            array("+263772893892"=>"13"),
//            array("+263773520519"=>"13"),
//            array("+263772428039"=>"13"),
//            array("+263773603863"=>"13"),
//            array("+263774161468"=>"13"),
//            array("+263733272056"=>"13"),
//            array("+263712510845"=>"13"),
//            array("+263774046920"=>"13"),
//            array("+263772776813"=>"13"),
//            array("+263775780154"=>"16"),
//            array("+263773129621"=>"13"),
//            array("+263774376802"=>"13"),
//            array("+263772407426"=>"14"),
//            array("+263775184380"=>"7"),
//            array("+263772337482"=>"13")
//        );

        $amounts = array(array("+263735497567"=>"13"),
            array("+263777497250"=>"13"),
            array("+263775007078"=>"13"),
            array("+263773549624"=>"13"),
            array("+263773395759"=>"13"),
            array("+263777528069"=>"13"));
        $this->setEntityManager();
        $users = array("+263773036911",
            "+263772739647",
            "+263774734754",
            "+263772136862",
            "+263773012055",
            "+263774133329",
            "+263776353022",
            "+263772929433",
            "+263776173976",
            "+263736930844",
            "+263772893150",
            "+263773232761",
            "+263782489690",
            "+263772425633",
            "+263778436147",
            "+263772581472",
            "+263775797614",
            "+263717069754",
            "+263773424220",
            "+263778915938",
            "+263733217128",
            "+263712333159",
            "+263772257979",
            "+263773261153",
            "+263773476226",
            "+263773476226",
            "+263774740084",
            "+263712325352",
            "+263778071305",
            "+263775774573",
            "+263775208153",
            "+263775184380",
            "+263774259024",
            "+263772408312",
            "+263772893892",
            "+263773520519",
            "+263772428039",
            "+263773603863",
            "+263774161468",
            "+263733272056",
            "+263712510845",
            "+263774046920",
            "+263772776813",
            "+263775780154",
            "+263773129621",
            "+263774376802",
            "+263772407426",
            "+263775184380",
            "+263772337482"
        );
        $dates = array(
            "2015-10-13",
            "2015-10-13",
            "2015-10-13",
            "2015-10-13",
            "2015-10-13",
            "2015-10-13",);
        for($s= 0; $s< count($amounts);$s++) {
            $usr = $amounts[$s];
            $i = 0;
            foreach ($usr as $phoneNumber => $amount_paid) {
               // die($phoneNumber);
                $result = $this->entity_manager->getRepository(Constants::ENTITY_USERS)->findOneByPhoneNumber($phoneNumber);
                if ($result != null) {

                    $ecocash = new EcocashPayments();
                    $ecocash->setUser($result);
                    $ecocash->setAmountPaid($amount_paid);
                    $dt_paid = new \DateTime($dates[$s]);
                    $timestamp = new \DateTime($dt_paid->format('Y-m-d 00:00:00'));
                    $ecocash->setDatePaid($timestamp);
                    $ecocash->setExcessAmount('0.50');
                    $ecocash->setSendState(true);

                    $this->entity_manager->persist($ecocash);
                    $this->entity_manager->flush();


//                    date_default_timezone_set("UTC");
//                    $now = new \DateTime();
//                    $timestamp = $now->getTimestamp();
//                $user_activity = new UserActivitiesData();
//                $str_amount = number_format($amount_paid, 2);
//                $user_activity->setMessage('You have paid $'.$str_amount.Constants::FOR_YOUR_POLICY_ACCOUNT)
//                    ->setTitle(self::SHIRI_FUNERAL_PLAN_PAYMENT)
//                    ->setUser($result)
//                    ->setDateTime($timestamp)
//                    ->setMsgId(Constants::SERVER_MSG);
//                $this->entity_manager->persist($user_activity);
//                $this->entity_manager->flush();


                }
            }
        }
    }

    public function insertEcoPayments(){
        $this->setEntityManager();
        $payers = array(
            "+263775233827"
        );
        //payee
        $new_payments = array(
            "+263775233827"
        );
        $datePaid = array(
            "2015-11-27 08:38:28"
        );
        $amounts = array(
            "12.50"
        );
        $refs = array(
            "6678604"
        );
        $excess_amounts = array(
            "00.00"
        );
        $i = 0;
        //todo change this to match
        $ptype = Constants::NETTCASH;//Constants::ECOCASH Constants::NETTCASH
        foreach($new_payments as $phoneNumber){
       die('IWE WABATWA !!!');
            $user = $this->entity_manager->getRepository(Constants::ENTITY_USERS)->findOneByPhoneNumber($phoneNumber);
          //  $datePaidfor = $my_record['datePaidfor'];
            $referenceid = '';
            if ($user != null) {


       //       echo('User id ==>'.' '.$user->getUserId().'<br/>');
               // $amount_pd = number_format('-2.00000',2);
             //   echo $amount_pd;
                $payment_type = $this->entity_manager->getRepository(Constants::ENTITY_PAYMENT_TYPES)->findOneById($ptype);
                if ($payment_type == null) {
                    $result ='<error>PAYMENT_TYPE_NOT_FOUND__'.'Ecocash'.'</error>';
                    return $result;
                }

                $dt_paid = $datePaid[$i];
                $amount_paid = $amounts[$i];
                $referenceid = $refs[$i];
                $excess_amount = $excess_amounts[$i];
                $payer_phoneNumber = $payers[$i];

                $i+=1;
                $subscribed_packages = $this->entity_manager->getRepository(Constants::ENTITY_SUBSCRIBED_PACKAGES)->findBy(array('user' => $user, 'status' => true),array('id' => 'ASC'));

               foreach ($subscribed_packages as $package) {

                   if ($package->getIsDependent()) {
                       $dep = $package->getDependent();
                       //  $dep = new UserDependents();
                       $rel = $dep->getRelationType();
                       // $rel =  new UserRelationshipTypes();
                       if ($rel->getId() < Constants::IMMEDIATE_FAMILY) {
                           continue;
                       }

                   }
//                   $pack = $package->getPackagePlan();
//                   $user_package_amount = $this->entity_manager->getRepository(Constants::ENTITY_PACKAGEPLANFIGURES)->findOneByPackagePlan($pack);
//                   //    $pack = new PackagePlans();


                   if (strcmp($payer_phoneNumber, $phoneNumber) == 0) {
                       $paidFor = Constants::MYSELF_PAYMENT;
                   } else {
                       $paidFor = Constants::FRIEND_PAYMENT;

                   }

                   //       die($phoneNumber);
                   $last_payment = $this->entity_manager->getRepository(Constants::ENTITY_USER_PAYMENTS)->findBy(array('subscribedPackage' => $package), array('monthPaidFor' => 'DESC'), 1);

                   date_default_timezone_set('UTC');
                   $date_now = new \DateTime("now");
                   $result = $date_now->format('Y-m-01 00:00:00');
                   $date_now = new \DateTime($result);
                   $number = '';
                   $monthPaidForDt = '';
                   $userPayment = new UserPayments();
                   if ($last_payment == null) {
                       $package->setDateActivated($date_now);
                       //todo enable here
                     $this->entity_manager->flush();
                       echo 'FIRST PAYMENT ==>'.'<br/>';
                       $month = date_format($date_now, 'm') . "";
                       $year = date_format($date_now, 'Y') . "";

                       $number = cal_days_in_month(CAL_GREGORIAN, $month, $year);
                       $monthPaidForDt = $year . '-' . $month . '-' . $number . ' 00:00:00';
                       $mpf_date = new \DateTime($monthPaidForDt);//  $timestamp = new \DateTime('2015-09-01 00:00:00');
                       // $result = $timestamp->format('Y-m-d 00:00:00');
                       //  $mpf_date = new \DateTime($mpf_date);
                       $userPayment->setMonthPaidFor($mpf_date);
                   } else {
                       $dt = $package->getDateActivated();
                       if ($date_now < $dt) {
                           $package->setDateActivated($date_now);
                           //todo enable here
                          $this->entity_manager->flush();
                       }
                       foreach ($last_payment as $pay) {
                           // $pay  = new UserPayments();
                           $timestamp = $pay->getMonthPaidFor();
                           $date = $timestamp;

                           $month = date_format($date, 'm') . "";
                           $year = date_format($date, 'Y') . "";
                           if ($month == 12) {
                               $year += 1;
                               $month = 1;
                           } else {
                               $month += 1;
                           }
                           $number = cal_days_in_month(CAL_GREGORIAN, $month, $year);
                           $monthPaidForDt = $year . '-' . $month . '-' . $number . ' 00:00:00';
                           $timestamp = new \DateTime($monthPaidForDt);//  $timestamp = new \DateTime('2015-09-01 00:00:00');
                           $check_payment = $this->entity_manager->getRepository(Constants::ENTITY_USER_PAYMENTS)->findBy(array('subscribedPackage' => $package,'monthPaidFor'=>$timestamp));
                           if($check_payment == null){
                               echo '*****>MONTH PAID FOR*****>>'.$monthPaidForDt.' '.$phoneNumber.'<br/>';
                               $userPayment->setMonthPaidFor($timestamp);
                           }else{
                               echo 'ALREADY PAID FOR '.$monthPaidForDt.' '.$phoneNumber.'<br/>';
                               continue;
                           }

                       }

                   }
                   $payer_user = $this->entity_manager->getRepository(Constants::ENTITY_USERS)->findOneByPhoneNumber($payer_phoneNumber);// "SELECT * FROM users WHERE phone_number = '".$phone_number."'";
                   //notify user
                   if (!$package->getIsDependent()) {
                       //  $package = new SubscribedPackages();
                       $pack = $package->getPackagePlan();
                       //  $pack= new PackagePlans();

                       if ($pack->getId() !== Constants::BUS_AND_CASH_ADITIONAL_BEN) {

                           $util = new Utils();
                           $db = new DBUtils($this->service_locator);

                           $gcm_reg_id = $user->getGcmRegid();
                           $amount_pd = number_format($amount_paid, 2);

                           $mdate = new \DateTime($monthPaidForDt);
                           $month_str = date_format($mdate, 'M');
                           $month = date_format($mdate, 'm');
                           $year = date_format($mdate, 'Y');
                           //$number = cal_days_in_month(CAL_GREGORIAN, $month, $year);
                           $monthPaidForDt = $month_str . '-' . $year;

//                           $res = $db->save_individual_client_messages(Constants::SHIRI_FUNERAL_PLAN_PAYMENT,
//                               Constants::YOU_HAVE_PAID . $amount_pd . ' ' . Constants::FOR_STR . $monthPaidForDt . ' ' . Constants::FOR_YOUR_POLICY_PREMIUM
//                               , $phoneNumber);

                           if ($gcm_reg_id === Constants::GCM_REG_ID_DEFAULT) {
                               echo('SMS =====>' . Constants::YOU_HAVE_PAID . $amount_pd . ' ' . Constants::FOR_STR . $monthPaidForDt . ' ' . Constants::FOR_YOUR_POLICY_PREMIUM . ' ' . $phoneNumber . '<br/>');

//                               $infobipSMSMessaging = new infobipSMSMessaging();
//                               $result = $infobipSMSMessaging->sendmsg($phoneNumber,
//                                   Constants::SHIRI_NAME, Constants::YOU_HAVE_PAID . $amount_pd . ' ' . Constants::FOR_STR . $monthPaidForDt . ' ' . Constants::FOR_YOUR_POLICY_PREMIUM);

                           } else {
                               echo('notif =====>' . Constants::YOU_HAVE_PAID . $amount_pd . ' ' . Constants::FOR_STR . $monthPaidForDt . ' ' . Constants::FOR_YOUR_POLICY_PREMIUM . ' ' . $phoneNumber . '<br/>');
//                               $util->notifyPayments($gcm_reg_id,
//                                   Constants::YOU_HAVE_PAID . $amount_pd . ' ' . Constants::FOR_STR . $monthPaidForDt . ' ' . Constants::FOR_YOUR_POLICY_PREMIUM,
//                                   $amount_pd);
                           }

                           if ($payer_user != null) {
                           if ($paidFor == Constants::FRIEND_PAYMENT) {

                               $totalAmountPaid = number_format($amount_paid, 2);
                               $message =  $payer_user->getFirstName() . ' ' . $payer_user->getLastName() . ' has paid $' . $totalAmountPaid . Constants::FOR_YOUR_POLICY_PREMIUM;
                               echo $message.'<br/>';
                               $message = Constants::YOU_HAVE_PAID . $totalAmountPaid . ' for ' . $user->getFirstName() . ' ' . $user->getLastName() . '. REF_ID :' . $referenceid;

                               echo $message.'<br/>';
                           }
                       }else{
                               $payer_user = $user;
                               echo ' USER PAYING FOR NOT FOUND ==>' . $payer_phoneNumber . '<br/>';
                           }


                       }

                   }
                   if ($payer_user == null) {
                       $payer_user = $user;
                   }
                   $totalPremiumAmount = $this->returnPremiumAmount($user);
                   $state = '*****USER*****';
                   if($pack->getId() == Constants::BUS_AND_CASH_ADITIONAL_BEN){
                       $state = '*****BUS AND CASH*****';
                   }elseif($package->getIsDependent()) {
                       $state = '*****DEPENDANT*****';
                   }
                   echo 'PREMIUM PER MONTH ==> :$'.$totalPremiumAmount.'  FOR ==>'.$phoneNumber.' '.$state.'<br/>';
                   if((int)$totalPremiumAmount > (int) $amount_paid){
                       if($package->getIsDependent()){
                           echo 'LESS AMOUNT PAID ==>'.$phoneNumber.'<br/>';
                           continue;
                       }
                   }

                   date_default_timezone_set('UTC');
                   $timestamp = new \DateTime($dt_paid);
                   $dt = $timestamp->format('Y-m-d H:m:s');

                   $userPayment->setSubscribedPackage($package);
                   $dtpaid = new \DateTime($dt);
                   $userPayment->setDatePaid($dtpaid);
                   //todo add month paid for data
                   $userPayment->setExternalRef($referenceid);
                   $userPayment->setPayee($payer_user);
                   $userPayment->setSendState(false);
                   $userPayment->setPaymentType($payment_type);
                  $this->entity_manager->persist($userPayment);

                   if (!$package->getIsDependent() && $pack->getId() !== Constants::BUS_AND_CASH_ADITIONAL_BEN) {

                       if($ptype == Constants::ECOCASH){
                       //todo enable this
                       $ecocash = new EcocashPayments();
                       $ecocash->setReferenceId($referenceid);
                       $ecocash->setUser($user);
                       $ecocash->setAmountPaid($amount_paid);
                       $ecocash->setDatePaid($dtpaid);
                       $ecocash->setExcessAmount($excess_amount);
                       $ecocash->setSendState(false);

                      $this->entity_manager->persist($ecocash);
                           echo('=============> Ecocash Payment <================' . '<br/>');
                   }elseif($ptype == Constants::NETTCASH){
                           //todo enable this
                           $nettcash = new NettcashPayments();
                           $nettcash->setTransactionId($referenceid);
                           $nettcash->setUser($user);
                           $nettcash->setAmountPaid($amount_paid);
                           $nettcash->setDatePaid($dtpaid->getTimestamp());
                           $nettcash->setExcessAmount($excess_amount);

                           $this->entity_manager->persist($nettcash);
                           echo('=============> Nettcash Payment <================' . '<br/>');
                       }
                   }
                          try{
                            $this->entity_manager->flush();
                              echo('=============> Payment saved <================' . '<br/>');
                          }catch (\Exception $ex){
                           die($ex);
                          }

               }


            }else{
                echo('User doesnt EXIST ==>'.' '.$phoneNumber.'<br/>');
            }

        }
    }


    public function insertEcoSendMessagesPayments(){
        $this->setEntityManager();
        $payers = array(
            "+263775788722"
        );
        //payee
        $new_payments = array(
            "+263775788722"
        );
        $datePaid = array(
            "2015-11-27 17:29:00"
        );
        $amounts = array(
            "12.00"
        );
        $refs = array(
            "MP151127.1729.B03400"
        );
        $excess_amounts = array(
            "00.00"
        );
        $i = 0;
        //todo change this to match
        $ptype = Constants::ECOCASH;//Constants::ECOCASH Constants::NETTCASH
        foreach($new_payments as $phoneNumber){
             die('IWE WABATWA !!!');
            $user = $this->entity_manager->getRepository(Constants::ENTITY_USERS)->findOneByPhoneNumber($phoneNumber);
            //  $datePaidfor = $my_record['datePaidfor'];
            $referenceid = '';
            if ($user != null) {


                //       echo('User id ==>'.' '.$user->getUserId().'<br/>');
                // $amount_pd = number_format('-2.00000',2);
                //   echo $amount_pd;
                $payment_type = $this->entity_manager->getRepository(Constants::ENTITY_PAYMENT_TYPES)->findOneById($ptype);
                if ($payment_type == null) {
                    $result ='<error>PAYMENT_TYPE_NOT_FOUND__'.'Ecocash'.'</error>';
                    return $result;
                }

                $dt_paid = $datePaid[$i];
                $amount_paid = $amounts[$i];
                $referenceid = $refs[$i];
                $excess_amount = $excess_amounts[$i];
                $payer_phoneNumber = $payers[$i];

                $i+=1;
                $subscribed_packages = $this->entity_manager->getRepository(Constants::ENTITY_SUBSCRIBED_PACKAGES)->findBy(array('user' => $user, 'status' => true),array('id' => 'ASC'));

                foreach ($subscribed_packages as $package) {

                    if ($package->getIsDependent()) {
                        $dep = $package->getDependent();
                        //  $dep = new UserDependents();
                        $rel = $dep->getRelationType();
                        // $rel =  new UserRelationshipTypes();
                        if ($rel->getId() < Constants::IMMEDIATE_FAMILY) {
                            continue;
                        }

                    }
//                   $pack = $package->getPackagePlan();
//                   $user_package_amount = $this->entity_manager->getRepository(Constants::ENTITY_PACKAGEPLANFIGURES)->findOneByPackagePlan($pack);
//                   //    $pack = new PackagePlans();


                    if (strcmp($payer_phoneNumber, $phoneNumber) == 0) {
                        $paidFor = Constants::MYSELF_PAYMENT;
                    } else {
                        $paidFor = Constants::FRIEND_PAYMENT;

                    }

                    //       die($phoneNumber);
                    $last_payment = $this->entity_manager->getRepository(Constants::ENTITY_USER_PAYMENTS)->findBy(array('subscribedPackage' => $package), array('monthPaidFor' => 'DESC'), 1);

                    date_default_timezone_set('UTC');
                    $date_now = new \DateTime("now");
                    $result = $date_now->format('Y-m-01 00:00:00');
                    $date_now = new \DateTime($result);
                    $number = '';
                    $monthPaidForDt = '';
                    $userPayment = new UserPayments();
                    if ($last_payment == null) {
                        $package->setDateActivated($date_now);
                        //todo enable here
                       // $this->entity_manager->flush();
                        echo 'FIRST PAYMENT ==>'.'<br/>';
                        $month = date_format($date_now, 'm') . "";
                        $year = date_format($date_now, 'Y') . "";

                        $number = cal_days_in_month(CAL_GREGORIAN, $month, $year);
                        $monthPaidForDt = $year . '-' . $month . '-' . $number . ' 00:00:00';
                        $mpf_date = new \DateTime($monthPaidForDt);//  $timestamp = new \DateTime('2015-09-01 00:00:00');
                        // $result = $timestamp->format('Y-m-d 00:00:00');
                        //  $mpf_date = new \DateTime($mpf_date);
                        $userPayment->setMonthPaidFor($mpf_date);
                    } else {
                        $dt = $package->getDateActivated();
                        if ($date_now < $dt) {
                            $package->setDateActivated($date_now);
                            //todo enable here
                          //  $this->entity_manager->flush();
                        }
                        foreach ($last_payment as $pay) {
                            // $pay  = new UserPayments();
                            $timestamp = $pay->getMonthPaidFor();
                            $date = $timestamp;

                            $month = date_format($date, 'm') . "";
                            $year = date_format($date, 'Y') . "";
                            if ($month == 12) {
                                $year += 1;
                                $month = 1;
                            } else {
                                $month += 1;
                            }
                            $number = cal_days_in_month(CAL_GREGORIAN, $month, $year);
                            $monthPaidForDt = $year . '-' . $month . '-' . $number . ' 00:00:00';
                            $timestamp = new \DateTime($monthPaidForDt);//  $timestamp = new \DateTime('2015-09-01 00:00:00');
                            $check_payment = $this->entity_manager->getRepository(Constants::ENTITY_USER_PAYMENTS)->findBy(array('subscribedPackage' => $package,'monthPaidFor'=>$timestamp));
                            if($check_payment == null){
                                echo '*****>MONTH PAID FOR*****>>'.$monthPaidForDt.' '.$phoneNumber.'<br/>';
                                $userPayment->setMonthPaidFor($timestamp);
                            }else{
                                echo 'ALREADY PAID FOR '.$monthPaidForDt.' '.$phoneNumber.'<br/>';
                                continue;
                            }

                        }

                    }
                    $payer_user = $this->entity_manager->getRepository(Constants::ENTITY_USERS)->findOneByPhoneNumber($payer_phoneNumber);// "SELECT * FROM users WHERE phone_number = '".$phone_number."'";
                    //notify user
                    if (!$package->getIsDependent()) {
                        //  $package = new SubscribedPackages();
                        $pack = $package->getPackagePlan();
                        //  $pack= new PackagePlans();

                        if ($pack->getId() !== Constants::BUS_AND_CASH_ADITIONAL_BEN) {

                            $util = new Utils();
                            $db = new DBUtils($this->service_locator);

                            $gcm_reg_id = $user->getGcmRegid();
                            $amount_pd = number_format($amount_paid, 2);

                            $mdate = new \DateTime($monthPaidForDt);
                            $month_str = date_format($mdate, 'M');
                            $month = date_format($mdate, 'm');
                            $year = date_format($mdate, 'Y');
                            //$number = cal_days_in_month(CAL_GREGORIAN, $month, $year);
                            $monthPaidForDt = $month_str . '-' . $year;

                           $res = $db->save_individual_client_messages(Constants::SHIRI_FUNERAL_PLAN_PAYMENT,
                               Constants::YOU_HAVE_PAID . $amount_pd . ' ' . Constants::FOR_STR . $monthPaidForDt . ' ' . Constants::FOR_YOUR_POLICY_PREMIUM
                               , $phoneNumber);

                            if ($gcm_reg_id === Constants::GCM_REG_ID_DEFAULT) {
                                echo('SMS =====>' . Constants::YOU_HAVE_PAID . $amount_pd . ' ' . Constants::FOR_STR . $monthPaidForDt . ' ' . Constants::FOR_YOUR_POLICY_PREMIUM . ' ' . $phoneNumber . '<br/>');

                               $infobipSMSMessaging = new infobipSMSMessaging();
                               $result = $infobipSMSMessaging->sendmsg($phoneNumber,
                                   Constants::SHIRI_NAME, Constants::YOU_HAVE_PAID . $amount_pd . ' ' . Constants::FOR_STR . $monthPaidForDt . ' ' . Constants::FOR_YOUR_POLICY_PREMIUM);

                            } else {
                                echo('notif =====>' . Constants::YOU_HAVE_PAID . $amount_pd . ' ' . Constants::FOR_STR . $monthPaidForDt . ' ' . Constants::FOR_YOUR_POLICY_PREMIUM . ' ' . $phoneNumber . '<br/>');
                               $util->notifyPayments($gcm_reg_id,
                                   Constants::YOU_HAVE_PAID . $amount_pd . ' ' . Constants::FOR_STR . $monthPaidForDt . ' ' . Constants::FOR_YOUR_POLICY_PREMIUM,
                                   $amount_pd);
                            }

                            if ($payer_user != null) {
                                if ($paidFor == Constants::FRIEND_PAYMENT) {

                                    $totalAmountPaid = number_format($amount_paid, 2);
                                    $gcm_reg_id = $user->getGcmRegid();
                                    //todo enable sending SMS or notification in this method
                                    $this->sendNotification($gcm_reg_id, $phoneNumber, $totalAmountPaid
                                        , $payer_user->getFirstName() . ' ' . $payer_user->getLastName() . ' has paid $' . $totalAmountPaid . Constants::FOR_YOUR_POLICY_PREMIUM);
                                    sleep(2);
                                    $payer_gcm_reg_id = $payer_user->getGcmRegid();
                                    //todo enable sending SMS or notification in this method
                                    $this->sendNotification($payer_gcm_reg_id, $payer_phoneNumber, $totalAmountPaid,
                                        Constants::YOU_HAVE_PAID . $totalAmountPaid . ' for ' . $user->getFirstName() . ' ' . $user->getLastName() . '. REF_ID :' . $referenceid);
                                }
                            }else{
                                $payer_user = $user;
                                echo ' USER PAYING FOR NOT FOUND ==>' . $payer_phoneNumber . '<br/>';
                            }


                        }

                    }
                    if ($payer_user == null) {
                        $payer_user = $user;
                    }
                    $totalPremiumAmount = $this->returnPremiumAmount($user);
                    $state = '*****USER*****';
                    if($pack->getId() == Constants::BUS_AND_CASH_ADITIONAL_BEN){
                        $state = '*****BUS AND CASH*****';
                    }elseif($package->getIsDependent()) {
                        $state = '*****DEPENDANT*****';
                    }
                    echo 'PREMIUM PER MONTH ==> :$'.$totalPremiumAmount.'  FOR ==>'.$phoneNumber.' '.$state.'<br/>';
                    if((int)$totalPremiumAmount > (int) $amount_paid){
                        if($package->getIsDependent()){
                            echo 'LESS AMOUNT PAID ==>'.$phoneNumber.'<br/>';
                            continue;
                        }
                    }

                }



            }else{
                echo('User doesnt EXIST ==>'.' '.$phoneNumber.'<br/>');
            }

        }
    }

    /**
     * @param $user
     * @return string
     */
    public function returnPremiumAmount($user)
    {
        $subscribed_packages = $this->entity_manager->getRepository(Constants::ENTITY_SUBSCRIBED_PACKAGES)->findBy(array('user' => $user, 'status' => true), array('id' => 'ASC'));
        $premium_amount = '0.00';
        $state = 1;
        if($subscribed_packages != null){
            foreach ($subscribed_packages as $package) {
                //  $package = new SubscribedPackages();
                $timestamp = $package->getDateActivated();
                $pack = $package->getPackagePlan();
                // $pack = new PackagePlans();
                if ($pack->getId() == Constants::IMM_FAMILY) {
                    continue;
                }
                $bandc_Id = Constants::BUS_AND_CASH_ADITIONAL_BEN;

                $query = $this->entity_manager->createQueryBuilder();
                $query->select(array('p'))
                    ->from(Constants::ENTITY_PACKAGEPLANFIGURES, 'p')
                    ->where($query->expr()->orX(
                        $query->expr()->eq('p.packagePlan', '?1')
                    ))
                    ->andWhere($query->expr()->orX(
                        $query->expr()->lte('p.dateEffective', '?2')
                    ))
                    ->setParameters(array(1 => $pack, 2 => $timestamp))
                    ->orderBy('p.id', 'DESC')
                    ->setMaxResults(1);
                $query = $query->getQuery();
                $data_result = $query->getResult();

                if ($data_result != null && is_array($data_result)) {
                    // $user_payments = new PackagePlansFigures();
                    foreach ($data_result as $figure) {
                        $premium_amount += $figure->getAmount();
                    }

                }


            }
        }
        $premium_amount = number_format($premium_amount, 2);
        return $premium_amount;//array('state'=>$state, 'amount'=>$premium_amount);
    }

    /**
     * @param $gcm_token_id
     * @param $phone_number
     * @param $amount
     * @param $message
     */
    public function sendNotification($gcm_token_id,$phone_number,$amount, $message){
        $util = new Utils();
        $db = new DBUtils($this->service_locator);
        echo $message.'<br/>';
        //todo enable this
        if(strcmp($gcm_token_id ,Constants::GCM_REG_ID_DEFAULT)== 0){

            $infobipSMSMessaging = new infobipSMSMessaging();
            //    $phone_number  = "+263783211562";
            $result =  $infobipSMSMessaging->sendmsg($phone_number,
                Constants::SHIRI_SMS_TITLE, $message);

        }else {
            $util->notifyPayments($gcm_token_id, $message,$amount);
        }

        $res = $db->save_individual_client_messages(Constants::SHIRI_FUNERAL_PLAN_PAYMENT,
            $message, $phone_number);


    }

    public function insertMigratedData(){
        $this->setEntityManager();
        $new_users = array(
            "+263717443654"
        );

        $jsGUrpOjsmQwi ='$jsGUrpOjsmQwi';
        $pincodes = array(
            "$2y$10$jsGUrpOjsmQwi/iaUvim2unpNtcahHlt09ihbkH.qkyb5x7PKaeDm"
        );
        $dob = array(
            "-21607200000"
        );
        $createdAt = array(
            "1447152176586"
        );
        $idnumbers = array(
            "75200386Z75"
        );
        $names = array("Kedwell"=>"Mukunga");
        $branches = array(
            "12"
        );
        $genderState = array(
            1
        );
        $gcm_ids = array(
            "registrationId"
        );
        $i = 0;
        foreach($names as $firstname => $lastname) {
          die('hey hey WABATWA!!!');
            $ref_user = $this->entity_manager->getRepository(Constants::ENTITY_USERS)->findOneByPhoneNumber('+263771548881');

            $branch_id = $branches[$i];
            $dateOfBirth = $dob[$i];
            $phoneNumber = $new_users[$i];
            $id_number = $idnumbers[$i];
            $created_at = $createdAt[$i];
            $user_pincode_hash = $pincodes[$i];
            $gender = $genderState[$i];
            $gcm_id = $gcm_ids[$i];
            $i += 1;

            $check_user = $this->entity_manager->getRepository(Constants::ENTITY_USERS)->findOneByPhoneNumber($phoneNumber);
            if($check_user != null){
                echo 'user exists '.$phoneNumber;
                continue;
            }

            $branch_res = $this->entity_manager->getRepository(Constants::ENTITY_BRANCHES)->findOneByBranchId($branch_id);

            if($gender == 1){
                $sex = true;
            }else
            $sex = false;
            $user = new Users();
            $user->setFirstName($firstname)
                ->setLastName($lastname)
                ->setPhoneNumber($phoneNumber)
                ->setIdNumber($id_number)
                ->setDateOfBirth($dateOfBirth)
                ->setCreatedAt($created_at)
                //->setPolicyNumber($policy_number)
                ->setPincode($user_pincode_hash)
                ->setGender($sex)
                ->setBranch($branch_res)
                ->setReferer($ref_user)
                ->setGcmRegid($gcm_id);
            // ->setNettcashRegistered($nettcash_reg_state);

            $this->entity_manager->persist($user);
            $plan_name = '1';
            $plan_name_res = $this->entity_manager->getRepository(Constants::ENTITY_PACKAGE_PLANS)->findOneById($plan_name);

            $mil = (int)$user->getCreatedAt();
            $seconds = $mil / 1000;
            $dt = date("Y-m-d 00:00:00", $seconds);
            $date = new \DateTime($dt);


            $package = new SubscribedPackages();
            $package->setDateActivated($date);
            $package->setUser($user);
            $package->setPackagePlan($plan_name_res);
            $package->setIsDependent(false);
            $package->setUser($user);
            $package->setStatus(true);
            $this->entity_manager->persist($package);

            date_default_timezone_set("UTC");
            $now = new \DateTime();
            $timestamp = $now->getTimestamp()*1000;

            $nettcash_user = new NettcashAccounts();
            $nettcash_user->setUser($user);
            $nettcash_user->setActivated(true);
            $nettcash_user->setDateCreated($created_at);
            $this->entity_manager->persist($nettcash_user);
//            $this->entity_manager->flush();

            $ad_updates = new AdminUpdates();
            $ad_updates->setUser($user);
            $ad_updates->setSendState(false);
            $this->entity_manager->persist($ad_updates);
            $this->entity_manager->flush();

            return 'Inserted Information FOR === >'.$firstname.' '.$lastname.' '.$phoneNumber;
        }

    }

    public function notifyUnpaidPolicies()
    {
        $this->setEntityManager();
        $db = new DBUtils($this->service_locator);
        $util = new Utils();
        $users = $this->entity_manager->getRepository(Constants::ENTITY_USERS)->findAll();
        $i = 0;
        foreach ($users as $user) {

            if($user->getUserId() == 1 || $user->getUserId() == 2 ||
                $user->getUserId() ==3 || $user->getUserId() == 9|| $user->getUserId() ==10
                || $user->getUserId() ==100 || $user->getUserId() ==4){
                continue;
            }
            $phone_number = $user->getPhoneNumber();
            $gcm_reg_id = $user->getGcmRegid();

            $message =  'Dear '.$user->getFirstName().' '.$user->getLastName() .', according to our records, you have not yet paid for November, Today is the last day for payment.';
//todo enable this
          //  $res = $db->save_individual_client_messages(Constants::SHIRI_FUNERAL_PLAN_DUE_PAYMENTS,$message , $phone_number);

            $query = $this->entity_manager->createQueryBuilder();
            $query->select(array('pack'))
                ->from('Application\Entity\SubscribedPackages', 'pack')
                ->where($query->expr()->orX(
                    $query->expr()->eq('pack.user', '?1')
                ))
                ->andWhere($query->expr()->orX(
                    $query->expr()->eq('pack.isDependent', '?2')
                ))
                ->setParameters(array(1 => $user, 2 => false))
                ->orderBy('pack.id', 'ASC')
                ->setMaxResults(1);
            $query = $query->getQuery();
            $user_data = $query->getResult();
            if ($user_data != null) {
                foreach ($user_data as $package) {
                    $user_Fpayments = $this->entity_manager->getRepository(Constants::ENTITY_USER_PAYMENTS)->findBy(array('subscribedPackage' => $package), array('monthPaidFor' => 'ASC'), 1);
                    if ($user_Fpayments != null) {
                      if ($gcm_reg_id === Constants::GCM_REG_ID_DEFAULT) {
                            echo('SMS =====>' . $message. ' ' . $phone_number . '<br/>');
//todo enable saving massage above
                               $infobipSMSMessaging = new infobipSMSMessaging();
                               // $result = $infobipSMSMessaging->sendmsg( $phone_number , Constants::SHIRI_NAME, $message);//Constants::WELCOME_YOUR_ACCOUNT_PINCODE_IS . $pincode);
                          if (empty($result)) {
                        echo 'not sent ===>'.$phone_number.'<br/>';
                    }
                      } else {
                            echo('notif =====>' .  $message .' '. $phone_number . '<br/>');
                             // $util->notifyPayments($gcm_reg_id,$message,'0.00');
                        }
                    }else {
                        echo 'PAID BEFORE ==> '.'<br/>';
                        date_default_timezone_set('UTC');
                        $owingMonth = new \DateTime();
                        $month = date_format($owingMonth, 'm') . "";
                        $year = date_format($owingMonth, 'Y') . "";
                        $number = cal_days_in_month(CAL_GREGORIAN, $month, $year);
                        $owingMonth = new \DateTime($year . '-' . $month . '-' . $number);
                        $owingMonth = $owingMonth->format('Y-m-d 00:00:00');
                        $sql = 'SELECT * FROM user_payments pay WHERE pay.subscribed_package_id = "'.$package->getId().'" AND pay.month_paid_for = "'.$owingMonth.'"';
                        $conn = $this->getEntityManager()->getConnection();
                        $stmt = $conn->prepare($sql);
                        $stmt->execute();
                        $payment = $stmt->fetch();
                        if($payment == null ){
                            if ($gcm_reg_id === Constants::GCM_REG_ID_DEFAULT) {
                                echo('SMS =====>' . $message. ' ' . $phone_number . '<br/>');

                                $infobipSMSMessaging = new infobipSMSMessaging();
                              //  $result = $infobipSMSMessaging->sendmsg( $phone_number , Constants::SHIRI_NAME, $message);//Constants::WELCOME_YOUR_ACCOUNT_PINCODE_IS . $pincode);
                                if (empty($result)) {
                                    echo 'not sent ===>'.$phone_number.'<br/>';
                                }
                            } else {
                                echo('notif =====>' .  $message.'  ' . $phone_number . '<br/>');
                               //   $util->notifyPayments($gcm_reg_id,$message,'0.00');
                            }
                        }
                    }
                }
            }
            //sleep(2);
        }
    }


}