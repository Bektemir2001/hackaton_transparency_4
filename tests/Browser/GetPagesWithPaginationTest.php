<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class GetPagesWithPaginationTest extends DuskTestCase
{
    public function testGetAllPagesWithPagination()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('http://zakupki.gov.kg/popp/view/order/list.xhtml'); // Замените URL на целевой сайт
            $pageCount = 1;

            while ($browser->waitFor('.ui-paginator-next', 5)) { // Замените '.ui-paginator-next' на селектор кнопки следующей страницы
                $html = $browser->driver->getPageSource();

                file_put_contents('page_' . $pageCount . '.html', $html); // Сохранить HTML в файл
                $browser->click('.ui-paginator-next'); // Кликнуть на кнопку следующей страницы
                $pageCount++;
            }

            // Сохранить HTML последней страницы (если нужно)
            $html = $browser->driver->getPageSource();
            file_put_contents('page_' . $pageCount . '.html', $html);
        });
    }
}
