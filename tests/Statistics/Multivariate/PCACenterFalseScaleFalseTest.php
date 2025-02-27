<?php

namespace MathPHP\Tests\Statistics\Multivariate;

use MathPHP\Functions\Map\Multi;
use MathPHP\LinearAlgebra\NumericMatrix;
use MathPHP\LinearAlgebra\MatrixFactory;
use MathPHP\SampleData;
use MathPHP\Statistics\Multivariate\PCA;
use MathPHP\Exception;

class PCACenterFalseScaleFalseTest extends \PHPUnit\Framework\TestCase
{
    /** @var PCA */
    private static $pca;

    /** @var NumericMatrix  */
    private static $matrix;

    /**
     * R code for expected values:
     *   library(mdatools)
     *   data = mtcars[,c(1:7,10,11)]
     *   model = pca(data, center=FALSE, scale=FALSE)
     * @throws Exception\MathException
     */
    public static function setUpBeforeClass(): void
    {
        $mtCars = new SampleData\MtCars();

        // Remove and categorical variables
        self::$matrix = MatrixFactory::create($mtCars->getData())->columnExclude(8)->columnExclude(7);
        self::$pca = new PCA(self::$matrix, false, false);
    }

    /**
     * @test The class returns the correct R-squared values
     *
     * R code for expected values:
     *   model$calres$expvar / 100
     */
    public function testRsq()
    {
        // Given
        $expected = [9.803400e-01,1.703780e-02,2.556893e-03,4.616958e-05,1.016372e-05,5.622890e-06,1.984531e-06,8.650104e-07,4.790115e-07];

        // When
        $R2 = self::$pca->getR2();

        // Then
        $this->assertEqualsWithDelta($expected, $R2, .00001);
    }

    /**
     * @test The class returns the correct cumulative R-squared values
     *
     * R code for expected values:
     *   model$calres$cumexpvar / 100
     */
    public function testCumRsq()
    {
        // Given
        $expected = [0.9803400, 0.9973778, 0.9999347, 0.9999809, 0.9999910, 0.9999967, 0.9999987, 0.9999995, 1.0000000];

        // When
        $cumR2 = self::$pca->getCumR2();

        // Then
        $this->assertEqualsWithDelta($expected, $cumR2, .00001);
    }

    /**
     * @test The class returns the correct loadings
     *
     * R code for expected values:
     *   model$loadings
     *
     * @throws \Exception
     */
    public function testLoadings()
    {
        // Given
        $expected = [
            [-0.051925627, -0.12165304,  0.817422861, -0.5413940877,  0.02689719,  0.008914525, -0.139583824, -5.675923e-03, -0.029963092],
            [-0.020557526, -0.01353417,  0.068143077,  0.0987603484,  0.20737048,  0.955091867,  0.152614784, -4.480184e-02, -0.067049682],
            [-0.852259286,  0.52236090,  0.009319093, -0.0221197348,  0.00760591, -0.010774634,  0.001562170, -3.801612e-05,  0.006378726],
            [-0.517193226, -0.84055123, -0.157539692,  0.0002310284, -0.03343415, -0.004003993, -0.004569087,  1.161989e-03, -0.003144782],
            [-0.010100772, -0.02134860,  0.107784378,  0.0359551626,  0.16536008, -0.105361770,  0.535767246,  8.016455e-01, -0.135664712],
            [-0.010676118, -0.00139196,  0.041311677,  0.1939265220,  0.14070864, -0.074352494, -0.283440144, -2.022529e-02, -0.924389171],
            [-0.051316434, -0.05996934,  0.527619081,  0.8018724122, -0.18540056, -0.067099385, -0.025981989, -3.218030e-02,  0.178333151],
            [-0.010389902, -0.02795905,  0.102769101,  0.0084776345,  0.30289807, -0.222173811,  0.693132180, -5.918419e-01, -0.129071518],
            [-0.009437259, -0.02818431,  0.001810469,  0.1211527714,  0.88421425, -0.130747874, -0.329711429,  6.001898e-02,  0.270543069],
        ];

        // And since each column could be multiplied by -1, we will compare the two and adjust.
        $loadings   = self::$pca->getLoadings();
        $load_array = $loadings->getMatrix();

        // Get an array that's roughly ones and negative ones.
        $quotiant = Multi::divide($expected[1], $load_array[1]);

        // Convert to exactly one or negative one. Cannot be zero.
        $signum = \array_map(
            function ($x) {
                return $x <=> 0;
            },
            $quotiant
        );
        $sign_change = MatrixFactory::diagonal($signum);

        // Multiplying a sign change matrix on the right changes column signs.
        $sign_adjusted = $loadings->multiply($sign_change);

        // Then
        $this->assertEqualsWithDelta($expected, $sign_adjusted->getMatrix(), .00001);
    }


    /**
     * @test The class returns the correct scores
     *
     * R code for expected values:
     *   model$calres$scores
     *   new = matrix(c(1:9), 1, 9)
     *   result = predict(model, new)
     *   result$scores
     *
     * @throws \Exception
     */
    public function testScores()
    {
        // Given
        $expected = [
            [-195.45787,  -12.817386,  11.367951,  0.07519595,  2.05857003,  0.63151851,  0.10467453,  0.150178317,  0.1934049675],
            [-195.48933,  -12.851323,  11.673952,  0.57369576,  1.99062642,  0.57498297,  0.01784738,  0.126999901,  0.0575522934],
            [-142.47876,  -25.925585,  16.007775,  1.35005628, -1.23661732, -0.35871646,  0.53615242, -0.071470892, -0.1487746327],
            [-279.11234,   38.275802,  14.008493, -0.20554881, -0.74520008,  0.03578839, -0.18746661, -0.209313261,  0.2166434815],
            [-399.44962,   37.337313,   1.390017, -2.56068832, -0.42438018,  0.57015871,  0.06388460, -0.022480529,  0.2311564234],
            [-248.26987,   25.601818,  12.178339,  2.97131461, -1.08083910,  0.34511522, -0.01670047, -0.481720506,  0.0767848565],
            [-435.38499,  -20.953066, -13.841554, -0.83891849, -0.86770335,  0.05234928, -0.27524798,  0.287312900,  0.3452018215],
            [-159.59972,   20.042888,  23.313928,  1.01934880,  0.85969188, -0.90915257, -0.18322610, -0.248616163, -0.1164567144],
            [-171.70640,  -10.761352,  18.305492,  4.34965233, -0.83680254, -1.20782456, -0.06067236, -0.109100155,  0.3130107590],
            [-208.66846,  -19.667545,   8.926250,  2.51978278,  1.41086733,  0.29499409,  0.03888733,  0.115448332, -0.1776447352],
            [-208.62655,  -19.533213,   8.098429,  3.75885795,  1.26197094,  0.24225412,  0.21871549,  0.104086445, -0.0286965165],
            [-330.19078,  -10.618571,  -1.842712,  1.09331648, -0.40465236,  1.24220074, -0.33046600, -0.029498112, -0.4859434363],
            [-330.24415,  -10.739579,  -1.015554,  0.70050126, -0.46536594,  1.26208378, -0.36491819, -0.034165903, -0.1629512703],
            [-330.15616,  -10.508165,  -2.519028,  2.16787414, -0.58897482,  1.21280590, -0.09635696, -0.036129848, -0.0749149763],
            [-510.07271,   71.523155,  -8.515848,  0.81760309,  1.00995796, -1.26802021, -0.05494029, -0.068593421,  0.1689117259],
            [-505.01189,   56.857170, -10.272760,  0.99331068,  0.65006815, -1.18834129, -0.12703496,  0.001227491, -0.1379543568],
            [-495.92884,   33.297763,  -9.496840, -1.21662325,  0.21317268, -0.98609566, -0.67101407,  0.193859553, -0.4710513012],
            [-104.08701,  -19.760062,  28.309142, -2.53084772, -0.43683215,  0.07765118, -0.59136373,  0.002910856, -0.3053175375],
            [ -94.14870,   -9.304667,  28.419917, -2.10844588,  1.07322012,  0.03513718,  0.06327073,  0.781929609,  0.3060786532],
            [ -97.19011,  -23.100201,  29.848878, -2.89600421, -0.52878762,  0.16044946, -0.64075206,  0.099298974, -0.0005034673],
            [-154.85385,  -22.862071,  15.053448,  2.92401373, -1.88014886, -0.38343656, -0.13273358,  0.363705787,  0.2198320477],
            [-350.54795,   36.818436,   2.203481, -0.02376334, -0.01946931,  1.13947448,  0.33144174, -0.341203331,  0.0899595113],
            [-338.62585,   29.507884,   2.093187,  0.79067507, -0.16121324,  1.22402113,  0.57331589, -0.038444975,  0.1119933721],
            [-426.79654,  -26.040713, -14.911842, -0.35007535, -0.76695882,  0.10517054,  0.06195653,  0.718601326, -0.0854363280],
            [-433.57111,   58.170054,   2.196507, -3.61609521, -0.06684543,  0.11888020, -0.09649695, -0.092110971,  0.1117928259],
            [-104.04578,  -18.948372,  23.831391, -0.28483159, -0.50333550,  0.08690478,  0.21090379,  0.055549130, -0.0072789186],
            [-152.01907,  -18.162467,  18.205138, -2.07093828,  0.62168334, -0.72719823,  0.89264763, -0.094189076, -0.2713488812],
            [-142.14577,  -50.350393,  18.109568, -3.87552017, -0.42163132, -0.40180349, -0.04249360, -0.615482184,  0.0716817655],
            [-437.57716,  -41.803832, -16.101679, -2.54635213, -0.56609648, -0.34449193,  1.49008531, -0.021591262, -0.0812773586],
            [-216.20281,  -75.151468,  -0.498672,  0.62582833,  1.96041194,  0.12038386, -0.16816853, -0.434748871,  0.0724077975],
            [-431.68366, -127.608466, -28.403655, -0.37292768,  0.12042128, -0.58497179, -0.59990301, -0.248999660,  0.2229792125],
            [-161.77699,  -32.447174,  12.507458,  2.03583649, -0.71655509, -0.76700401,  0.35823800,  0.214037980, -0.2659514877],
        ];

        // And since each column could be multiplied by -1, we will compare the two and adjust.
        $scores = self::$pca->getScores();
        $score_array = $scores->getMatrix();

        // Get an array that's roughly ones and negative ones.
        $quotiant = Multi::divide($expected[1], $score_array[1]);

        // Convert to exactly one or negative one. Cannot be zero.
        $signum = \array_map(
            function ($x) {
                return $x <=> 0;
            },
            $quotiant
        );
        $signature = MatrixFactory::diagonal($signum);

        // Multiplying a sign change matrix on the right changes column signs.
        $sign_adjusted = $scores->multiply($signature);

        // Then
        $this->assertEqualsWithDelta($expected, $sign_adjusted->getMatrix(), .00001);

        // And Given
        $expected = MatrixFactory::create([[-5.360422, -2.956055, 5.67008, 7.705329, 11.08508, -2.525982, 3.526032, -0.6236968, -3.731516]]);
        $sign_adjusted = $expected->multiply($signature);

        // When
        $scores = self::$pca->getScores(MatrixFactory::create([[1,2,3,4,5,6,7,8,9]]));

        // Then
        $this->assertEqualsWithDelta($sign_adjusted->getMatrix(), $scores->getMatrix(), .00001);
    }

    /**
     * @test The class returns the correct eigenvalues
     *
     * R code for expected values:
     *   model$eigenvals
     */
    public function testEigenvalues()
    {
        // Given
        $expected = [1.826540e+04, 1.625914e+03, 2.110626e+02, 4.518513e+00, 9.968080e-01, 5.514463e-01, 1.945801e-01, 8.485820e-02, 4.699142e-02];

        // When
        $eigenvalues = self::$pca->getEigenvalues()->getVector();

        // Then
        $this->assertEqualsWithDelta($expected, $eigenvalues, .001);
    }

    /**
     * @test The class returns the correct critical T² distances
     *
     * R code for expected values:
     *   model$T2lim
     */
    public function testCriticalT2()
    {
        // Given
        $expected = [4.159615, 6.852714, 9.40913, 12.01948, 14.76453, 17.69939, 20.87304, 24.33584, 28.14389];

        // When
        $criticalT2 = self::$pca->getCriticalT2();

        // Then
        $this->assertEqualsWithDelta($expected, $criticalT2, .00001);
    }

    /**
     * @test The class returns the correct critical Q distances
     *
     * R code for expected values:
     *   model$Qlim
     */
    public function testCriticalQ()
    {
        // Given
        $expected = [6457.782, 801.2888, 19.984743, 5.209983, 2.6011363, 0.9505228, 0.4051835, 0.17606577, 0];

        // When
        $criticalQ = self::$pca->getCriticalQ();

        // Then
        $this->assertEqualsWithDelta($expected, $criticalQ, .001);
    }

    /**
     * @test The class returns the correct T² distances
     *
     * R code for expected values:
     *   model$calres$T2
     *
     * @throws \Exception
     */
    public function testGetT²Distances()
    {
        // Given
        $expected = [
            [0.39724238,  0.4955328,  1.0107352,  1.011984,  5.262136,  5.985137,  6.041416,  6.307194,  7.103198],
            [0.39737026,  0.4961819,  1.0394939,  1.112160,  5.086388,  5.685733,  5.687369,  5.877438,  5.947924],
            [0.21108118,  0.6132141,  1.6348012,  2.037216,  3.570928,  3.804204,  5.280742,  5.340937,  5.811956],
            [0.81004162,  1.6865601,  2.4689015,  2.478230,  3.035183,  3.037505,  3.218021,  3.734317,  4.733100],
            [1.65910279,  2.4931653,  2.5008682,  3.948585,  4.129212,  4.718541,  4.739505,  4.745460,  5.882543],
            [0.64091037,  1.0330621,  1.6243372,  3.573587,  4.745230,  4.961151,  4.962583,  7.697190,  7.822658],
            [1.97104332,  2.2337116,  2.9975178,  3.152903,  3.908023,  3.912991,  4.302140,  5.274921,  7.810786],
            [0.26485827,  0.5052022,  2.6721295,  2.901541,  3.642781,  5.141223,  5.313665,  6.042054,  6.330663],
            [0.30656478,  0.3758509,  1.7117580,  5.888900,  6.591194,  9.235880,  9.254788,  9.395055, 11.480019],
            [0.45275462,  0.6841810,  1.0018329,  2.403666,  4.400057,  4.557816,  4.565583,  4.722648,  5.394208],
            [0.45257279,  0.6808486,  0.9423145,  4.061793,  5.659039,  5.765431,  6.011144,  6.138815,  6.156339],
            [1.13365042,  1.2011102,  1.2146473,  1.478561,  1.642785,  4.440156,  5.001102,  5.011356, 10.036535],
            [1.13401690,  1.2030229,  1.2071346,  1.315474,  1.532676,  4.420314,  5.104319,  5.118075,  5.683136],
            [1.13341273,  1.1994769,  1.2247746,  2.262394,  2.610304,  5.276850,  5.324540,  5.339923,  5.459354],
            [2.70528767,  5.7658823,  6.0549964,  6.202586,  7.225596, 10.140463, 10.155967, 10.211413, 10.818568],
            [2.65187148,  4.5859895,  5.0067042,  5.224546,  5.648375,  8.208427,  8.291319,  8.291337,  8.696333],
            [2.55733716,  3.2206870,  3.5802472,  3.907047,  3.952623,  5.715429,  8.028194,  8.471067, 13.192963],
            [0.11265288,  0.3462616,  3.5412324,  4.955404,  5.146787,  5.157718,  6.954012,  6.954112,  8.937847],
            [0.09216752,  0.1439657,  3.3639893,  4.345500,  5.500683,  5.502921,  5.523484, 12.728583, 14.722220],
            [0.09821853,  0.4174781,  3.9694498,  5.821141,  6.101578,  6.148249,  8.257111,  8.373308,  8.373313],
            [0.24934077,  0.5620521,  1.4654632,  3.353146,  6.898484,  7.165019,  7.255515,  8.814368,  9.842768],
            [1.27774486,  2.0887865,  2.1081432,  2.108268,  2.108648,  4.462481,  5.026745,  6.398673,  6.570889],
            [1.19231078,  1.7132519,  1.7307193,  1.868747,  1.894813,  4.610903,  6.299229,  6.316646,  6.583556],
            [1.89404828,  2.2997606,  3.1862551,  3.213313,  3.803266,  3.823318,  3.843035,  9.928316, 10.083650],
            [1.95465422,  3.9791245,  3.9983590,  6.885379,  6.889861,  6.915481,  6.963310,  7.063294,  7.329249],
            [0.11256367,  0.3273745,  2.5915613,  2.609473,  2.863564,  2.877255,  3.105730,  3.142093,  3.143220],
            [0.24029539,  0.4376568,  1.7589568,  2.705857,  3.093482,  4.052159,  8.145032,  8.249578,  9.816459],
            [0.21009568,  1.7268604,  3.0343241,  6.350443,  6.528738,  6.821419,  6.830694, 11.294811, 11.404156],
            [1.99094179,  3.0364915,  4.0700993,  5.501651,  5.823057,  6.038199, 17.443073, 17.448566, 17.589145],
            [0.48603990,  3.8650339,  3.8660253,  3.952498,  7.806997,  7.833270,  7.978534, 10.205848, 10.317419],
            [1.93767299, 11.6801964, 14.8965362, 14.927242, 14.941786, 15.562135, 17.410681, 18.141319, 19.199375],
            [0.27213397,  0.9020262,  1.5256914,  2.440765,  2.955724,  4.022225,  4.681417,  5.221283,  6.726451],
        ];

        // When
        $T²Distances = self::$pca->getT2Distances()->getMatrix();

        // Then
        $this->assertEqualsWithDelta($expected, $T²Distances, .00001);
    }

    /**
     * @test The class returns the correct T² distances
     *
     * R code for expected values:
     *   new = matrix(c(1:9), 1, 9)
     *   result = predict(model, new)
     *   result$T2
     *
     * @throws \Exception
     */
    public function testT2WithNewData()
    {
        // Given
        $expected = [[0.000298777, 0.0055268, 0.1336984, 13.24218, 136.482, 148.0491, 211.9109, 216.495, 512.808]];
        $newdata  = MatrixFactory::create([[1,2,3,4,5,6,7,8,9]]);

        // When
        $T²Distances = self::$pca->getT2Distances($newdata)->getMatrix();

        // Then
        $this->assertEqualsWithDelta($expected, $T²Distances, .0001);
    }

    /**
     * @test The class returns the correct Q residuals
     *
     * R code for expected values:
     *   model$calres$Q
     *
     * @throws \Exception
     */
    public function testGetQResiduals()
    {
        // Given
        $expected = [
            [  298.2288, 133.943413,  4.7130964, 4.70744195, 0.46973139, 0.07091577, 0.059959008, 3.740548e-02, 3.979586e-26],
            [  306.0798, 140.923252,  4.6420856, 4.31295872, 0.35036518, 0.01975977, 0.019441241, 3.312266e-03, 4.015322e-26],
            [  932.1801, 260.044118,  3.7952533, 1.97260130, 0.44337890, 0.31470140, 0.027241980, 2.213389e-02, 2.037588e-26],
            [ 1661.9997, 196.962625,  0.7247444, 0.68249414, 0.12717098, 0.12589017, 0.090746439, 4.693440e-02, 9.512859e-26],
            [ 1403.1274,   9.052473,  7.1203241, 0.56319940, 0.38310086, 0.05801991, 0.053938666, 5.343329e-02, 1.393383e-25],
            [  814.1193, 158.666207, 10.3542577, 1.52554715, 0.35733398, 0.23822947, 0.237950560, 5.895914e-03, 4.794795e-26],
            [  632.3565, 193.325515,  1.7369082, 1.03312399, 0.28021490, 0.27747445, 0.201713000, 1.191643e-01, 1.486794e-25],
            [  947.9702, 546.252870,  2.7136445, 1.67457249, 0.93550236, 0.10894397, 0.075372163, 1.356217e-02, 1.602867e-26],
            [  472.0898, 356.283148, 21.1921138, 2.27263839, 1.57239989, 0.11355971, 0.109878579, 9.797574e-02, 1.682266e-26],
            [  474.9635,  88.151204,  8.4732716, 2.12396633, 0.13341971, 0.04639819, 0.044885969, 3.155765e-02, 5.481025e-26],
            [  462.9707,  81.424318, 15.8397647, 1.71075165, 0.11818101, 0.05949395, 0.011657478, 8.234901e-04, 4.165777e-26],
            [  119.3980,   6.643953,  3.2483661, 2.05302514, 1.88928161, 0.34621894, 0.237011162, 2.361410e-01, 1.475347e-25],
            [  118.8309,   3.492358,  2.4610087, 1.97030663, 1.75374117, 0.16088571, 0.027720425, 2.655312e-02, 1.475858e-25],
            [  123.3007,  12.879174,  6.5336701, 1.83399177, 1.48710043, 0.01620228, 0.006917620, 5.612254e-03, 1.117796e-25],
            [ 5191.4140,  75.852284,  3.3326198, 2.66414499, 1.64412991, 0.03625466, 0.033236228, 2.853117e-02, 1.326442e-25],
            [ 3341.1240, 108.386175,  2.8565805, 1.86991441, 1.44732581, 0.03517079, 0.019032911, 1.903140e-02, 9.175498e-26],
            [ 1202.1387,  93.397692,  3.2077301, 1.72755797, 1.68211538, 0.70973074, 0.259470855, 2.218893e-01, 3.169109e-25],
            [ 1198.9126, 808.452516,  7.0449805, 0.63979038, 0.44896804, 0.44293834, 0.093227272, 9.321880e-02, 1.069873e-26],
            [  900.5762, 813.999346,  6.3076813, 1.86213730, 0.71033586, 0.70910124, 0.705098055, 9.368414e-02, 5.929276e-27],
            [ 1433.6874, 900.068166,  9.1126245, 0.72578412, 0.44616777, 0.42042374, 0.009860540, 2.534793e-07, 7.204827e-27],
            [  761.7107, 239.036357, 12.4300659, 3.88020957, 0.34524982, 0.19822623, 0.180608029, 4.832613e-02, 2.049742e-26],
            [ 1361.9862,   6.389040,  1.5337119, 1.53314719, 1.53276814, 0.23436605, 0.124512427, 8.092714e-03, 1.025334e-25],
            [  877.5888,   6.873529,  2.4920961, 1.86692908, 1.84093937, 0.34271164, 0.014020532, 1.254252e-02, 1.382984e-25],
            [  901.7311, 223.612385,  1.2493653, 1.12681251, 0.53858669, 0.52752584, 0.523687231, 7.299366e-03, 8.350389e-26],
            [ 3401.7048,  17.949684, 13.1250391, 0.04889454, 0.04442623, 0.03029373, 0.020982067, 1.249764e-02, 2.381670e-25],
            [  927.3656, 568.324841,  0.3896472, 0.30851817, 0.05517154, 0.04761910, 0.003138689, 5.298266e-05, 7.130181e-27],
            [  667.3857, 337.510481,  6.0834144, 1.79462902, 1.40813885, 0.87932159, 0.082501797, 7.363022e-02, 1.095748e-26],
            [ 2878.8631, 343.701105, 15.7446379, 0.72498132, 0.54720834, 0.38576230, 0.383956594, 5.138276e-03, 1.388977e-26],
            [ 2015.9749, 268.414546,  9.1504755, 2.66656633, 2.34610111, 2.22742642, 0.007072192, 6.606009e-03, 1.129688e-25],
            [ 5652.4638,   4.720572,  4.4718985, 4.08023737, 0.23702240, 0.22253012, 0.194249470, 5.242889e-03, 2.764977e-26],
            [17091.6555, 807.734998,  0.9673725, 0.82829747, 0.81379618, 0.47160418, 0.111720560, 4.971973e-02, 1.678438e-25],
            [ 1214.7469, 161.927755,  5.4912535, 1.34662326, 0.83317206, 0.24487691, 0.116542450, 7.073019e-02, 2.059957e-26],
        ];

        // When
        $qResiduals = self::$pca->getQResiduals()->getMatrix();

        // Then
        $this->assertEqualsWithDelta($expected, $qResiduals, .0001);
    }

    /**
     * @test The class returns the correct Q residuals
     *
     * library(mdatools)
     *   new = matrix(c(1:9), 1, 9)
     *   result = predict(model, new)
     *   result$Q
     *
     * @throws \Exception
     */
    public function testQWithNewData()
    {
        // Given
        $expected = [[256.2659, 247.5276, 215.3778, 156.0057, 33.1267, 26.74611, 14.31321, 13.92422, 7.19096e-28]];
        $newData  = MatrixFactory::create([[1,2,3,4,5,6,7,8,9]]);

        // When
        $qResiduals = self::$pca->getQResiduals($newData)->getMatrix();

        // Then
        $this->assertEqualsWithDelta($expected, $qResiduals, .0001);
    }
}
