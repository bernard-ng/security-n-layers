import '../sass/app.scss';

import '../materialize/js/waves';
import '../materialize/js/sidenav';
import '../materialize/js/forms';

document.addEventListener('DOMContentLoaded', function () {
    const elems = document.querySelectorAll('.sidenav');
    const instances = M.Sidenav.init(elems, options);
});
