/*!
 * Ext JS Library 3.0.0
 * Copyright(c) 2006-2009 Ext JS, LLC
 * licensing@extjs.com
 * http://www.extjs.com/license
 */
Ext.ns('Ext.ux.grid');

/**
 * @class Ext.ux.grid.TableGrid
 * @extends Ext.grid.GridPanel
 * A Grid which creates itself from an existing HTML table element.
 * @history
 * 2007-03-01 Original version by Nige "Animal" White
 * 2007-03-10 jvs Slightly refactored to reuse existing classes * @constructor
 * @param {String/HTMLElement/Ext.Element} table The table element from which this grid will be created -
 * The table MUST have some type of size defined for the grid to fill. The container will be
 * automatically set to position relative if it isn't already.
 * @param {Object} config A config object that sets properties on this grid and has two additional (optional)
 * properties: fields and columns which allow for customizing data fields and columns for this grid.
 */
Ext.ux.grid.TableGridConfig = function(table, config)
{
    config = config || {};
    Ext.apply(this, config);
    var cf = config.fields || [], ch = config.columns || [];
	var table_height = Ext.getDom(table).offsetHeight;

    table = Ext.get(table);

    var fields = [], cols = [];
    var headers = table.query("thead th");
    for (var i = 0, h; h = headers[i]; i++) {
        var text = h.innerHTML;
        var name = 'tcol-' + i;

		var a_dataType = h.getAttribute('dataType')

		if (a_dataType == undefined)
		{
			var field_config = {
						            name: name,
						            mapping: 'td:nth(' + (i + 1) + ')/@innerHTML'
						        };
		}
		else
		{
			var field_config = {
						            name: name,
						            type: a_dataType,
						            mapping: 'td:nth(' + (i + 1) + ')/@innerHTML'
						        };
		}

        fields.push(Ext.applyIf(cf[i] ||
        {}, field_config));

		var a_locked = h.getAttribute('locked')
		var a_sortable = h.getAttribute('sortable')

		var cur_locked = (a_locked == undefined)?false:(a_locked == 'no'?false:true);
		var cur_sortable = (a_sortable == undefined)?false:(a_sortable == 'no'?false:true);

		var cols_config = {
		    'header': text,
		    'dataIndex': name,
		    'width': h.offsetWidth+5,
		    'tooltip': h.title,
		    'locked': cur_locked,
		    'sortable': cur_sortable
		};

        cols.push(Ext.applyIf(ch[i] ||
        {}, cols_config));
    }

    var ds = new Ext.data.Store({
        reader: new Ext.data.XmlReader({
            record: 'tbody tr'
        }, fields)
    });

	ds.loadData(table.dom);

    if (config.remove !== false) {
        table.remove();
    }

	return {ds: ds, cols: cols, height: table_height*1+31};
};
