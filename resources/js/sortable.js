import Sortable from "sortablejs";


console.log('testing, testing one two three');


export default function (Alpine) {
    Alpine.directive('sortable-source', (el) => {
        el.sortable = Sortable.create(el, {
            group: 'shared',
            put: false,
            sort: false,
            dataIdAttr: 'x-sortable-item',
            onSort() {
                el.dispatchEvent(new CustomEvent('sorted', {
                    detail: el.sortable.toArray().map(id => parseInt(id))
                }));
            }
        })
        }
    )

    Alpine.directive('sortable-target', (el) => {
        el.sortable = Sortable.create(el, {
            group: 'shared',
            put: true,
            filter: '.global-module',
            dataIdAttr: 'x-sortable-item',
            onSort() {
                el.dispatchEvent(new CustomEvent('sorted', {
                    detail: el.sortable.toArray().map(id => parseInt(id))
                }));
            }
        })
    })
}
