<section class="shopprogress">
    <nav>
        <?php if($_GET['p'] == 'warenkorb') :  ?>
            <a href="warenkorb"><span style="font-family: Roboto-Bold;">Warenkorb</span></a>
        <?php else : ?>
            <a href="warenkorb">Warenkorb</a>
        <?php endif; ?>

        <a><i class="fa fa-dot-circle-o"></i></a>

        <?php if($_GET['p'] == 'versand-und-bezahlung') :  ?>
            <a href="versand-und-bezahlung"><span style="font-family: Roboto-Bold;">Versand & Bezahlung</span></a>
        <?php else : ?>
            <a href="versand-und-bezahlung">Versand & Bezahlung</a>
        <?php endif; ?>

        <a><i class="fa fa-dot-circle-o"></i></a>
        <?php if($_GET['p'] == 'abschluss-der-bestellung') :  ?>
            <a href="abschluss-der-bestellung"><span style="font-family: Roboto-Bold;">Überprüfen</span></a>
        <?php else : ?>
            <a href="abschluss-der-bestellung">Überprüfen</a>
        <?php endif; ?>

    </nav>
</section>