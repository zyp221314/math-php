<?php
namespace MathPHP\Tests\LinearAlgebra;

use MathPHP\LinearAlgebra\MatrixFactory;
use MathPHP\LinearAlgebra\Matrix;
use MathPHP\LinearAlgebra\Reduction;

class MatrixReductionsTest extends \PHPUnit\Framework\TestCase
{
    use \MathPHP\Tests\LinearAlgebra\MatrixDataProvider;

    /**
     * @test         RREF
     * @dataProvider dataProviderForRref
     * @param        array $A
     * @param        array $R
     * @throws       \Exception
     */
    public function testRref(array $A, array $R)
    {
        // Given
        $A    = MatrixFactory::create($A);
        $R    = MatrixFactory::create($R);

        // When
        $rref = $A->rref();

        // Then
        $this->assertEquals($R->getMatrix(), $rref->getMatrix(), '', 0.000001);
        $this->assertTrue($rref->isRref());
        $this->assertTrue($rref->isRef());
    }

    /**
     * @test         RREF directly
     * @dataProvider dataProviderForRref
     * @param        array $A
     * @param        array $R
     * @throws       \Exception
     */
    public function testRrefDirectly(array $A, array $R)
    {
        // Given
        $A    = MatrixFactory::create($A);
        $R    = MatrixFactory::create($R);

        // When
        $rref = Reduction\ReducedRowEchelonForm::reduce($A);

        // Then
        $this->assertEquals($R->getMatrix(), $rref->getMatrix(), '', 0.000001);
        $this->assertTrue($rref->isRref());
        $this->assertTrue($rref->isRef());
    }

    /**
     * @return array
     */
    public function dataProviderForRref(): array
    {
        return [
            [
                [
                    [1, 2, 3],
                    [2, 3, 4],
                    [3, 4, 5],
                ],
                [
                    [1, 0, -1],
                    [0, 1, 2],
                    [0, 0, 0],
                ],
            ],
            [
                [
                    [1, 3, -1],
                    [0, 1, 7],
                ],
                [
                    [1, 0, -22],
                    [0, 1, 7],
                ],
            ],
            [
                [
                    [1, 2, 1],
                    [-2, -3, 1],
                    [3, 5, 0],
                ],
                [
                    [1, 0, -5],
                    [0, 1, 3],
                    [0, 0, 0],
                ],
            ],
            [
                [
                    [0, 3, -6, 6, 4, -5],
                    [3, -7, 8, -5, 8, 9],
                    [3, -9, 12, -9, 6, 15],
                ],
                [
                    [1, 0, -2, 3, 0, -24],
                    [0, 1, -2, 2, 0, -7],
                    [0, 0, 0, 0, 1, 4],
                ],
            ],
            [
                [
                    [0, 2, 8, -7],
                    [2, -2, 4, 0],
                    [-3, 4, -2, -5],
                ],
                [
                    [1, 0, 6, 0],
                    [0, 1, 4, 0],
                    [0, 0, 0, 1],
                ],
            ],
            [
                [
                    [1, -2, 3, 9],
                    [-1, 3, 0, -4],
                    [2, -5, 5, 17],
                ],
                [
                    [1, 0, 0, 1],
                    [0, 1, 0, -1],
                    [0, 0, 1, 2],
                ],
            ],
            [
                [
                    [1, 0, -2, 1, 0],
                    [0, -1, -3, 1, 3],
                    [-2, -1, 1, -1, 3],
                    [0, 3, 9, 0, -12],
                ],
                [
                    [1, 0, -2, 0, 1],
                    [0, 1, 3, 0, -4],
                    [0, 0, 0, 1, -1],
                    [0, 0, 0, 0, 0],
                ],
            ],
            [
                [
                    [1, 1, 4, 1, 2],
                    [0, 1, 2, 1, 1],
                    [0, 0, 0, 1, 2],
                    [1, -1, 0, 0, 2],
                    [2, 1, 6, 0, 1],
                ],
                [
                    [1, 0, 2, 0, 1],
                    [0, 1, 2, 0, -1],
                    [0, 0, 0, 1, 2],
                    [0, 0, 0, 0, 0],
                    [0, 0, 0, 0, 0],
                ],
            ],
            [
                [
                    [1, 2, 0, -1, 1, -10],
                    [1, 3, 1, 1, -1, -9],
                    [2, 5, 1, 0, 0, -19],
                    [3, 6, 0, 0, -6, -27],
                    [1, 5, 3, 5, -5, -7],
                ],
                [
                    [1, 0, -2, 0, -10, -7],
                    [0, 1, 1, 0, 4, -1],
                    [0, 0, 0, 1, -3, 1],
                    [0, 0, 0, 0, 0, 0],
                    [0, 0, 0, 0, 0, 0],
                ],
            ],
            [
                [
                    [-4, 3, 1, 5, -8],
                    [6, 0, 9, 2, 6],
                    [-1, 4, 4, 0, 2],
                    [8, -1, 3, 4, 0],
                    [5, 9, -7, -7, 1],
                ],
                [
                    [1, 0, 0, 0, 0],
                    [0, 1, 0, 0, 0],
                    [0, 0, 1, 0, 0],
                    [0, 0, 0, 1, 0],
                    [0, 0, 0, 0, 1],
                ],
            ],
            [
                [
                    [4, 7],
                    [2, 6],
                ],
                [
                    [1, 0],
                    [0, 1],
                ],
            ],
            [
                [
                    [4, 3],
                    [3, 2],
                ],
                [
                    [1, 0],
                    [0, 1],
                ],
            ],
            [
                [
                    [1, 2],
                    [3, 4],
                ],
                [
                    [1, 0],
                    [0, 1],
                ],
            ],
            [
                [
                    [3, 1],
                    [3, 4],
                ],
                [
                    [1, 0],
                    [0, 1],
                ],
            ],
            [
                [
                    [1, 2, 3],
                    [0, 4, 5],
                    [1, 0, 6],
                ],
                [
                    [1, 0, 0],
                    [0, 1, 0],
                    [0, 0, 1],
                ],
            ],
            [
                [
                    [7, 2, 1],
                    [0, 3, -1],
                    [-3, 4, -2],
                ],
                [
                    [1, 0, 0],
                    [0, 1, 0],
                    [0, 0, 1],
                ],
            ],
            [
                [
                    [3, 6, 6, 8],
                    [4, 5, 3, 2],
                    [2, 2, 2, 3],
                    [6, 8, 4, 2],
                ],
                [
                    [1, 0, 0, 0],
                    [0, 1, 0, 0],
                    [0, 0, 1, 0],
                    [0, 0, 0, 1],
                ],
            ],
            [
                [
                    [0, 0],
                    [0, 1],
                ],
                [
                    [0, 1],
                    [0, 0],
                ],
            ],
            [
                [
                    [1, 1, 1, 1, 1],
                    [0, 1, 1, 1, 1],
                    [0, 0, 0, 0, 1],
                ],
                [
                    [1, 0, 0, 0, 0],
                    [0, 1, 1, 1, 0],
                    [0, 0, 0 ,0, 1],
                ],
            ],
            [
                [
                    [0, 0],
                    [1, 1],
                    [-1, 0],
                    [0, -1],
                    [0, 0],
                    [0, 0],
                    [0, 0],
                    [0, 0],
                    [1, 1],
                ],
                [
                    [1, 0],
                    [0, 1],
                    [0, 0],
                    [0, 0],
                    [0, 0],
                    [0, 0],
                    [0, 0],
                    [0, 0],
                    [0, 0],
                ],
            ],
            [
                [
                    [1,  2,  3,  4,  3,  1],
                    [2,  4,  6,  2,  6,  2],
                    [3,  6, 18,  9,  9, -6],
                    [4,  8, 12, 10, 12,  4],
                    [5, 10, 24, 11, 15, -4],
                ],
                [
                    [1,  2,  0,  0,  3, 4],
                    [0,  0,  1,  0,  0, -1],
                    [0,  0,  0,  1,  0, 0],
                    [0,  0,  0,  0,  0, 0],
                    [0,  0,  0,  0,  0, 0],
                ],
            ],
            [
                [
                    [1, 2, 3, 4, 3, 1],
                    [2, 4, 6, 2, 6, 2],
                    [3, 6, 18, 9, 9, -6],
                    [4, 8, 12, 10, 12, 4],
                    [5, 10, 24, 11, 15, -4]
                ],
                [
                    [1, 2, 0, 0, 3, 4],
                    [0, 0, 1, 0, 0, -1],
                    [0, 0, 0, 1, 0, 0],
                    [0, 0, 0, 0, 0, 0],
                    [0, 0, 0, 0, 0, 0],
                ],
            ],
            [
                [
                    [0, 1],
                    [1, 2],
                    [0, 5],
                ],
                [
                    [1, 0],
                    [0, 1],
                    [0, 0],
                ],
            ],
            [
                [
                    [1, 0, 1, 0, 1, 0],
                    [1, 0, 1, 0, 0, 1],
                    [1, 0, 0, 1, 1, 0],
                    [1, 0, 0, 1, 0, 1],
                    [0, 1, 0, 1, 1, 0],
                    [0, 1, 0, 1, 0, 1],
                    [0, 1, 1, 0, 1, 0],
                    [0, 1, 1, 0, 0, 1],
                ],
                [
                    [1, 0, 0, 1, 0, 1],
                    [0, 1, 0, 1, 0, 1],
                    [0, 0, 1, -1, 0, 0],
                    [0, 0, 0, 0, 1, -1],
                    [0, 0, 0, 0, 0, 0],
                    [0, 0, 0, 0, 0, 0],
                    [0, 0, 0, 0, 0, 0],
                    [0, 0, 0, 0, 0, 0],
                ],
            ],
            [
                [
                    [3, -4, 2],
                    [-2, 6, 2],
                    [4, 2, 10],
                ],
                [
                    [1, 0, 2],
                    [0, 1, 1],
                    [0, 0, 0],
                ],
            ],
            [
                [
                    [3, 4, 2],
                    [-2, 6, 2],
                    [4, 2, 10],
                ],
                [
                    [1, 0, 0],
                    [0, 1, 0],
                    [0, 0, 1],
                ],
            ],
            [
                [
                    [3, -4, 2],
                    [2, 6, 2],
                    [4, 2, 10],
                ],
                [
                    [1, 0, 0],
                    [0, 1, 0],
                    [0, 0, 1],
                ],
            ],
            [
                [
                    [3, -4, 2],
                    [2, 6, 2],
                    [-4, 2, 10],
                ],
                [
                    [1, 0, 0],
                    [0, 1, 0],
                    [0, 0, 1],
                ],
            ],
            [
                [
                    [3, -4, 2],
                    [-2, 6, 2],
                    [-4, 2, 10],
                ],
                [
                    [1, 0, 0],
                    [0, 1, 0],
                    [0, 0, 1],
                ],
            ],
            [
                [
                    [-3, -4, 2],
                    [-2, 6, 2],
                    [4, 2, 10],
                ],
                [
                    [1, 0, 0],
                    [0, 1, 0],
                    [0, 0, 1],
                ],
            ],
            [
                [
                    [2, 0, -1, 0, 0],
                    [1, 0, 0, -1, 0],
                    [3, 0, 0, -2, -1],
                    [0, 1, 0, 0, -2],
                    [0, 1, -1, 0, 0]
                ],
                [
                    [1, 0, 0, 0, -1],
                    [0, 1, 0, 0, -2],
                    [0, 0, 1, 0, -2],
                    [0, 0, 0, 1, -1],
                    [0, 0, 0, 0, 0],
                ],
            ],
            [
                [
                    [2, -1, 4, 3, 2, 3, 4, 4],
                    [-1, 2, 3, 2, 1, 2, 3, 3],
                    [4, 3, 2, 1, 2, 3, 4, 4],
                    [2, 1, 2, 1, 2, 1, 2, 2],
                    [3, 2, 3, 2, 1, 2, 3, 3],
                    [3, 2, 3, 2, 1, 2, 1, 2],
                    [4, 3, 4, 3, 2, 1, 2, 2],
                    [4, 3, 4, 3, 2, 2, 2, 2],
                ],
                [
                    [1, 0, 0, 0, 0, 0, 0, 0],
                    [0, 1, 0, 0, 0, 0, 0, 0],
                    [0, 0, 1, 0, 0, 0, 0, 0],
                    [0, 0, 0, 1, 0, 0, 0, 0],
                    [0, 0, 0, 0, 1, 0, 0, 0],
                    [0, 0, 0, 0, 0, 1, 0, 0],
                    [0, 0, 0, 0, 0, 0, 1, 0],
                    [0, 0, 0, 0, 0, 0, 0, 1],
                ],
            ],
            [
                [
                    [5, -7, 6],
                    [-9, 5, 5],
                    [1, 3, 11],
                ],
                [
                    [1, 0, 0],
                    [0, 1, 0],
                    [0, 0, 1],
                ],
            ],
            [
                [
                    [1, 2, 3, 0, 0, 0],
                    [0, 0, 1, 1, 0, 1],
                    [0, 0, 0, 1, 1, 1],
                ],
                [
                    [1, 2, 0, 0, 3, 0],
                    [0, 0, 1, 0, -1, 0],
                    [0, 0, 0, 1, 1, 1],
                ],
            ],
            [
                [
                    [1, 2, 1],
                    [-2, -3, 1],
                    [3, 5, 0]
                ],
                [
                    [1, 0, -5],
                    [0, 1, 3],
                    [0, 0, 0],
                ],
            ],
            [
                [
                    [-7, -6, -12, -33],
                    [5, 5, 7, 24],
                    [1, 0, 4, 5],
                ],
                [
                    [1, 0, 0, -3],
                    [0, 1, 0, 5],
                    [0, 0, 1, 2],
                ],
            ],
            [
                [
                    [1, -1, 2, 1],
                    [2, 1, 1, 8],
                    [1, 1, 0, 5],
                ],
                [
                    [1, 0, 1, 3],
                    [0, 1, -1, 2],
                    [0, 0, 0, 0],
                ],
            ],
            [
                [
                    [2, 1, 7, -7, 2],
                    [-3, 4, -5, -6, 3],
                    [1, 1, 4, -5, 2],
                ],
                [
                    [1, 0, 3, -2, 0],
                    [0, 1, 1, -3, 0],
                    [0, 0, 0, 0, 1],
                ],
            ],
            [
                [
                    [2, -3, 1, 7, 14],
                    [2, 8, -4, 5, -1],
                    [1, 3, -3, 0, 4],
                    [-5, 2, 3, 4, -19],
                ],
                [
                    [1, 0, 0, 0, 1],
                    [0, 1, 0, 0, -3],
                    [0, 0, 1, 0, -4],
                    [0, 0, 0, 1, 1],
                ],
            ],
            [
                [
                    [3, 4, -1, 2, 6],
                    [1, -2, 3, 1, 2],
                    [0, 10, -10, -1, 1],
                ],
                [
                    [1, 0, 1, 4 / 5, 0],
                    [0, 1, -1, -1 / 10, 0],
                    [0, 0, 0, 0, 1],
                ],
            ],
            [
                [
                    [2, 4, 5, 7, -26],
                    [1, 2, 1, -1, -4],
                    [-2, -4, 1, 11, -10],
                ],
                [
                    [1, 2, 0, -4, 2],
                    [0, 0, 1, 3, -6],
                    [0, 0, 0, 0 ,0],
                ],
            ],
            [
                [
                    [1, 2, 8, -7, -2],
                    [3, 2, 12, -5, 6],
                    [-1, 1, 1, -5, -10],
                ],
                [
                    [1, 0, 2, 1, 0],
                    [0, 1, 3, -4, 0],
                    [0, 0, 0, 0, 1],
                ],
            ],
            [
                [
                    [2, 1, 7, -2, 4],
                    [3, -2, 0, 11, 13],
                    [1, 1, 5, -3, 1],
                ],
                [
                    [1, 0, 2, 1, 3],
                    [0, 1, 3, -4, -2],
                    [0, 0, 0, 0, 0],
                ],
            ],
            [
                [
                    [2, 3, -1, -9, -16],
                    [1, 2, 1, 0, 0],
                    [-1, 2, 3, 4, 8],
                ],
                [
                    [1, 0, 0, 2, 3],
                    [0, 1, 0, -3, -5],
                    [0, 0, 1, 4, 7],
                ],
            ],
            [
                [
                    [2, 3, 19, -4, 2],
                    [1, 2, 12, -3, 1],
                    [-1, 2, 8, -5, 1],
                ],
                [
                    [1, 0, 2, 1, 0],
                    [0, 1, 5, -2, 0],
                    [0, 0, 0 ,0, 1],
                ],
            ],
            [
                [
                    [-1, 5, 0, 0, -8],
                    [-2, 5, 5, 2, 9],
                    [-3, -1, 3, 1, 3],
                    [7, 6, 5, 1, 30],
                ],
                [
                    [1, 0, 0, 0, 3],
                    [0, 1, 0, 0, -1],
                    [0, 0, 1, 0, 2],
                    [0, 0, 0, 1, 5],
                ],
            ],
            [
                [
                    [1, 2, -4, -1, 0, 32],
                    [1, 3, -7, 0, -1, 33],
                    [1, 0, 2, -2, 3, 22],
                ],
                [
                    [1, 0, 2, 0, 5, 6],
                    [0, 1, -3, 0, -2, 9],
                    [0, 0, 0, 1, 1, -8],
                ],
            ],
            [
                [
                    [2, 1, 6],
                    [-1, -1, -2],
                    [3, 4, 4],
                    [3, 5, 2],
                ],
                [
                    [1, 0, 4],
                    [0, 1, -2],
                    [0, 0, 0],
                    [0, 0, 0],
                ],
            ],
            [
                [
                    [2, 1, 5, 10],
                    [1, -3, -1, -2],
                    [4, -2, 6, 12],
                ],
                [
                    [1, 0, 2, 4],
                    [0, 1, 1, 2],
                    [0, 0, 0, 0],
                ],
            ],
            [
                [
                    [1, 2, -4],
                    [-3, -1, -3],
                    [-2, 1, -7],
                ],
                [
                    [1, 0, 2],
                    [0, 1, -3],
                    [0, 0, 0],
                ],
            ],
            [
                [
                    [1, 1, 1],
                    [-4, -3, -2],
                    [3, 2, 1],
                ],
                [
                    [1, 0, -1],
                    [0, 1, 2],
                    [0, 0, 0],
                ],
            ],
            [
                [
                    [1, 2, -1, -1],
                    [2, 4, -1, 4],
                    [-1, -2, 3, 5],
                ],
                [
                    [1, 2, 0, 0],
                    [0, 0, 1, 0],
                    [0, 0, 0, 1],
                ],
            ],
            [
                [
                    [1, 1, -1, 1],
                    [2, 1, -1, 3],
                    [1, 4, -4, -2],
                    [2, 0, 1, 2],
                ],
                [
                    [1, 0, 0, 2],
                    [0, 1, 0, -3],
                    [0, 0, 1, -2],
                    [0, 0, 0, 0],
                ],
            ],
            [
                [
                    [4, 4, 2, 108],
                    [1, 1, 1, 30],
                    [2, -1, 0, 0],
                ],
                [
                    [1, 0, 0, 8],
                    [0, 1, 0, 16],
                    [0, 0, 1, 6],
                ],
            ],
            [
                [
                    [1, 1, 1, 1, 66],
                    [1, -4, 0, 0, 0],
                    [4, 4, 2, 2, 252],
                ],
                [
                    [1, 0, 0, 0, 48],
                    [0, 1, 0, 0, 12],
                    [0, 0, 1, 1, 6],
                ],
            ],
            [
                [
                    [5, 0, 0, 0],
                    [-6, 1, 0, 0],
                    [4, 6, 8, 0],
                    [6, 7, 7, -1],
                ],
                [
                    [1, 0, 0, 0],
                    [0, 1, 0, 0],
                    [0, 0, 1, 0],
                    [0, 0, 0, 1],
                ],
            ],
            [
                [
                    [5, -6, 4, 6],
                    [0, 1, 6, 7],
                    [0, 0, 8, 7],
                    [0, 0, 0, -1]
                ],
                [
                    [1, 0, 0, 0],
                    [0, 1, 0, 0],
                    [0, 0, 1, 0],
                    [0, 0, 0, 1],
                ],
            ],
            [
                [
                    [25, -30, 20, 30],
                    [-30, 37, -18, -29],
                    [20, -18, 116, 122],
                    [30, -29, 122, 135],
                ],
                [
                    [1, 0, 0, 0],
                    [0, 1, 0, 0],
                    [0, 0, 1, 0],
                    [0, 0, 0, 1],
                ]
            ],
            [
                [
                    [113, 60, 74, -6],
                    [60, 86, 97, -7],
                    [74, 97, 113, -7],
                    [-6, -7, -7, 1],
                ],
                [
                    [1, 0, 0, 0],
                    [0, 1, 0, 0],
                    [0, 0, 1, 0],
                    [0, 0, 0, 1],
                ],
            ],
            [
                [
                    [5, 0, 0, 0],
                    [-6, 1, 0, 0],
                    [4, 6, 0, 0],
                    [6, 7, 7, -1],
                ],
                [
                    [1, 0, 0, 0],
                    [0, 1, 0, 0],
                    [0, 0, 1, -1 / 7],
                    [0, 0, 0, 0],
                ],
            ],
            [
                [
                    [5, -6, 4, 6],
                    [0, 1, 6, 7],
                    [0, 0, 0, 7],
                    [0, 0, 0, -1]
                ],
                [
                    [1, 0, 8, 0],
                    [0, 1, 6, 0],
                    [0, 0, 0, 1],
                    [0, 0, 0, 0],
                ],
            ],
            [
                [
                    [113, 60, 42, -6],
                    [60, 86, 49, -7],
                    [42, 49, 49, -7],
                    [-6, -7, -7, 1],
                ],
                [
                    [1, 0, 0, 0],
                    [0, 1, 0, 0],
                    [0, 0, 1, -1 / 7],
                    [0, 0, 0, 0],
                ],
            ],
            [
                [
                    [25, -30, 20, 30],
                    [-30, 37, -18, -29],
                    [20, -18, 52, 66],
                    [30, -29, 66, 135],
                ],
                [
                    [1, 0, 8, 0],
                    [0, 1, 6, 0],
                    [0, 0, 0, 1],
                    [0, 0, 0, 0],
                ],
            ],
            [
                [
                    [172, 124, 45, 0],
                    [124, 92, 30, 0],
                    [45, 30, 25, 0],
                    [0, 0, 0, 0],
                ],
                [
                    [1, 0, 0, 0],
                    [0, 1, 0, 0],
                    [0, 0, 1, 0],
                    [0, 0, 0, 0],
                ],
            ],
            [
                [
                    [18, 36, 54, 41],
                    [36, 81, 126, 83],
                    [54, 126, 198, 125],
                    [41, 83, 125, 102],
                ],
                [
                    [1, 0, -1, 0],
                    [0, 1, 2, 0],
                    [0, 0, 0, 1],
                    [0, 0, 0, 0],
                ],
            ],
            [
                [
                    [82, 98, 110, 48],
                    [98, 118, 133, 60],
                    [110, 133, 151, 66],
                    [48, 60, 66, 48],
                ],
                [
                    [1, 0, 0, -6],
                    [0, 1, 0, 10],
                    [0, 0, 1, -4],
                    [0, 0, 0, 0],
                ]
            ]
        ];
    }

    /**
     * @test         isRref on rref matrix should return true
     * @dataProvider dataProviderForNonsingularMatrix
     * @param        array $A
     * @throws       \Exception
     */
    public function testRrefIsRref(array $A)
    {
        // Given
        $A = MatrixFactory::create($A);

        // When
        $rref = $A->rref();

        // Then
        $this->assertTrue($rref->isRref());
        $this->assertTrue($rref->isRef());
    }

    /**
     * @test         isRef on ref matrix should return true
     * @dataProvider dataProviderForNonsingularMatrix
     * @param        $A
     * @throws       \Exception
     */
    public function testRefIsRef(array $A)
    {
        // Given
        $A = MatrixFactory::create($A);

        // When
        $ref = $A->ref();

        // Then
        $this->assertTrue($ref->isRef());
    }

    /**
     * @test    rref lazy load is the same as the computed and returned value.
     * @throws \Exception
     */
    public function testRrefAlreadyComputed()
    {
        // Given
        $A = new Matrix([
            [ 4,  1,  2,  -3],
            [-3,  3, -1,   4],
            [-1,  2,  5,   1],
            [ 5,  4,  3,  -1],
        ]);

        // When
        $rref1 = $A->rref(); // computes rref
        $rref2 = $A->rref(); // simply gets already-computed rref

        // Then
        $this->assertEquals($rref1, $rref2);
    }

    /**
     * @test   ref lazy load is the same as the computed and returned value.
     * @throws \Exception
     */
    public function testRefAlreadyComputed()
    {
        // Given
        $A = new Matrix([
            [ 4,  1,  2,  -3],
            [-3,  3, -1,   4],
            [-1,  2,  5,   1],
            [ 5,  4,  3,  -1],
        ]);

        // When
        $ref1 = $A->ref(); // computes ref
        $ref2 = $A->ref(); // simply gets already-computed ref

        // Then
        $this->assertEquals($ref1, $ref2);
    }

    /**
     * @test         rowReductionToEchelonForm method of ref
     * @dataProvider dataProviderForRowReductionToEchelonForm
     * @param        array $A
     * @param        array $R
     * @throws       \Exception
     */
    public function testRowReductionToEchelonForm(array $A, array $R)
    {
        // Given
        $A   = MatrixFactory::create($A);
        $R   = MatrixFactory::create($R);

        // When
        list($ref, $swaps) = Reduction\RowEchelonForm::rowReductionToEchelonForm($A);
        $ref = MatrixFactory::create($ref);

        // Then
        $this->assertEquals($R->getMatrix(), $ref->getMatrix(), '', 0.000001);
        $this->assertTrue($ref->isRef());
    }

    /**
     * @return array
     */
    public function dataProviderForRowReductionToEchelonForm(): array
    {
        return [
            [
                [
                    [1, 2, 0],
                    [-1, 1, 1],
                    [1, 2, 3],
                ],
                [
                    [1, 2, 0],
                    [0, 1, 1 / 3],
                    [0, 0, 1],
                ],
            ],
            [
                [
                    [0, 2, 0],
                    [-1, 1, 1],
                    [1, 2, 3],
                ],
                [
                    [1, -1, -1],
                    [0, 1, 0],
                    [0, 0, 1],
                ],
            ],
            [
                [
                    [0, 2, 0],
                    [0, 1, 1],
                    [1, 2, 3],
                ],
                [
                    [1, 2, 3],
                    [0, 1, 1],
                    [0, 0, 1],
                ],
            ],
            [
                [
                    [1, 2, 0],
                    [0, 1, 1],
                    [0, 2, 3],
                ],
                [
                    [1, 2, 0],
                    [0, 1, 1],
                    [0, 0, 1],
                ],
            ],
            [
                [
                    [2, 5, 4],
                    [2, 4, 6],
                    [8, 7, 5],
                    [6, 4, 5],
                    [6, 2, 3],
                ],
                [
                    [1, 5 / 2, 2],
                    [0, 1, -2],
                    [0, 0, 1],
                    [0, 0, 0],
                    [0, 0, 0],
                ],
            ],
            [
                [
                    [1, 0, -2, 1, 0],
                    [0, -1, -3, 1, 3],
                    [-2, -1, 1, -1, 3],
                    [0, 3, 9, 0, -12],
                ],
                [
                    [1, 0, -2, 1, 0],
                    [0, 1, 3, -1, -3],
                    [0, 0, 0, 1, -1],
                    [0, 0, 0, 0, 0],
                ],
            ],
            [
                [
                    [5, 4, 8],
                    [7, 7, 5],
                    [6, 2, 4],
                ],
                [
                    [1, 4 / 5, 8 / 5],
                    [0, 1, -31 / 7],
                    [0, 0, 1],
                ],
            ],
            [
                [
                    [2, 0, -1, 0, 0],
                    [1, 0, 0, -1, 0],
                    [3, 0, 0, -2, -1],
                    [0, 1, 0, 0, -2],
                    [0, 1, -1, 0, 0]
                ],
                [
                    [1, 0, -1 / 2, 0, 0],
                    [0, 1, 0, 0, -2],
                    [0, 0, 1, -4 / 3, -2 / 3],
                    [0, 0, 0, 1, -1],
                    [0, 0, 0, 0, 0],
                ],
            ],
            [
                [
                    [2, -1, 4, 3, 2, 3, 4, 4],
                    [-1, 2, 3, 2, 1, 2, 3, 3],
                    [4, 3, 2, 1, 2, 3, 4, 4],
                    [2, 1, 2, 1, 2, 1, 2, 2],
                    [3, 2, 3, 2, 1, 2, 3, 3],
                    [3, 2, 3, 2, 1, 2, 1, 2],
                    [4, 3, 4, 3, 2, 1, 2, 2],
                    [4, 3, 4, 3, 2, 2, 2, 2],
                ],
                [
                    [1, -1 / 2, 2, 3 / 2, 1, 3 / 2, 2, 2],
                    [0, 1, 10 / 3, 7 / 3, 4 / 3, 7 / 3, 10 / 3, 10 / 3],
                    [0, 0, 1, 25 / 34, 13 / 34, 11 / 17, 31 / 34, 31 / 34],
                    [0, 0, 0, 1, -11 / 5, 18 / 5, 13 / 5, 13 / 5],
                    [0, 0, 0, 0, 1, 2, 2, 2],
                    [0, 0, 0, 0, 0, 1, 1, 1],
                    [0, 0, 0, 0, 0, 0, 1, 1 / 2],
                    [0, 0, 0, 0, 0, 0, 0, 1],
                ],
            ],
            [
                [
                    [0]
                ],
                [
                    [0],
                ],
            ],
            [
                [
                    [1]
                ],
                [
                    [1],
                ],
            ],
            [
                [
                    [5]
                ],
                [
                    [1],
                ],
            ],
            [
                [
                    [0, 0],
                    [0, 0]
                ],
                [
                    [0, 0],
                    [0, 0],
                ],
            ],
            [
                [
                    [0, 0],
                    [0, 1]
                ],
                [
                    [0, 1],
                    [0, 0],
                ],
            ],
            [
                [
                    [1, 0],
                    [0, 0]
                ],
                [
                    [1, 0],
                    [0, 0],
                ],
            ],
            [
                [
                    [0, 0],
                    [1, 0]
                ],
                [
                    [1, 0],
                    [0, 0],
                ],
            ],
            [
                [
                    [0, 0],
                    [1, 1]
                ],
                [
                    [1, 1],
                    [0, 0],
                ],
            ],
            [
                [
                    [0, 1],
                    [0, 1]
                ],
                [
                    [0, 1],
                    [0, 0],
                ],
            ],
            [
                [
                    [1, 0],
                    [1, 0]
                ],
                [
                    [1, 0],
                    [0, 0],
                ],
            ],
            [
                [
                    [1, 1],
                    [1, 1]
                ],
                [
                    [1, 1],
                    [0, 0],
                ],
            ],
            [
                [
                    [2, 6],
                    [1, 3]
                ],
                [
                    [1, 3],
                    [0, 0],
                ],
            ],
            [
                [
                    [3, 6],
                    [1, 2]
                ],
                [
                    [1, 2],
                    [0, 0],
                ],
            ],
            [
                [
                    [1, 2],
                    [1, 2]
                ],
                [
                    [1, 2],
                    [0, 0],
                ],
            ],
            [
                [
                    [1, 2, 3],
                    [2, 3, 4],
                    [3, 4, 5],
                ],
                [
                    [1, 2, 3],
                    [0, 1, 2],
                    [0, 0, 0],
                ],
            ],
            [
                [
                    [1, 2, 1],
                    [-2, -3, 1],
                    [3, 5, 0],
                ],
                [
                    [1, 2, 1],
                    [0, 1, 3],
                    [0, 0, 0],
                ],
            ],
            [
                [
                    [1, -1, 2],
                    [2, 1, 1],
                    [1, 1, 0],
                ],
                [
                    [1, -1, 2],
                    [0, 1, -1],
                    [0, 0, 0],
                ],
            ],
            [
                [
                    [1, 0, 1],
                    [0, 1, -1],
                    [0, 0, 0],
                ],
                [
                    [1, 0, 1],
                    [0, 1, -1],
                    [0, 0, 0],
                ],
            ],
            [
                [
                    [1, 2, 3],
                    [1, 3, 1],
                    [3, 4, 7],
                ],
                [
                    [1, 2, 3],
                    [0, 1, -2],
                    [0, 0, 1],
                ],
            ],
            [
                [
                    [1, 0, 0],
                    [-2, 0, 0],
                    [4, 6, 1],
                ],
                [
                    [1, 0, 0],
                    [0, 1, 1 / 6],
                    [0, 0, 0],
                ],
            ],
            [
                [
                    [1, 1, 4, 1, 2],
                    [0, 1, 2, 1, 1],
                    [0, 0, 0, 1, 2],
                    [1, -1, 0, 0, 2],
                    [2, 1, 6, 0, 1],
                ],
                [
                    [1, 1, 4, 1, 2],
                    [0, 1, 2, 1, 1],
                    [0, 0, 0, 1, 2],
                    [0, 0, 0, 0, 0],
                    [0, 0, 0, 0, 0],
                ],
            ],
            // This is an interesting case because of the minuscule values that are for all intents and purposes zero.
            // If the minuscule zero-like values are not handled properly, the zero value will be used as a pivot,
            // instead of being interchanged with a non-zero row.
            [
                [
                    [0, 1, 4, 2, 3, 3, 4, 4],
                    [1, 0, 3, 1, 2, 2, 3, 3],
                    [4, 3, 0, 2, 3, 3, 4, 4],
                    [3, 2, 1, 1, 2, 2, 3, 3],
                    [2, 1, 2, 0, 1, 1, 2, 2],
                    [3, 2, 3, 1, 2, 0, 1, 2],
                    [4, 3, 4, 2, 3, 1, 0, 2],
                    [4, 3, 4, 2, 3, 2, 2, 0],
                ],
                [
                    [1, 0, 3, 1, 2, 2, 3, 3],
                    [0, 1, 4, 2, 3, 3, 4, 4],
                    [0, 0, 1, 1 / 3, 7 / 12, 7 / 12, 5 / 6, 5 / 6],
                    [0, 0, 0, 1, 1, 1, 1, 1],
                    [0, 0, 0, 0, 1, 5, 6, 4],
                    [0, 0, 0, 0, 0, 1, 0, 0],
                    [0, 0, 0, 0, 0, 0, 1, -1],
                    [0, 0, 0, 0, 0, 0, 0, 0],
                ],
            ],
        ];
    }
}
