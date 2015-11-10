/**
 * Minimizable Ext Windows for Ext 2.1 or higher
 *  
 * @author Omer Dawelbeit (omerio)
 * @copyright (c) 2008, Omer Dawelbeit (omer.dawelbeit@gmail.com) http://www.dawelbeit.info
 * @license GPL v3
 * License details: http://www.gnu.org/licenses/gpl.html 
 * 
	This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>
	
 * This Addon requires the ExtJS Library, which is distributed under the terms of the GPL v3 (from V2.1)
 * See http://extjs.com/license for more info 
 */
Ext.ns('Ext.ux');


Ext.ux.Window = Ext.extend(Ext.Window, {
    // configurables
    // anything what is here can be configured from outside
    /* an image to set as the icon background a 48 x 48 image */
    minimizedIconCls: '',
    minimizedIconLeft: 0,
    minimizedIconTop: 0,
    
    /**
     *
     */
    initComponent: function(){
        /* Ext.apply(this, {
         items: []
         }); */
        // call parent
        Ext.ux.Window.superclass.initComponent.apply(this, arguments);
    },
    /**
     *
     */
    onRender: function(){
        Ext.ux.Window.superclass.onRender.apply(this, arguments);
    },
    /**
     *
     */
    afterRender: function(){
        // call parent
        Ext.ux.Window.superclass.afterRender.apply(this, arguments);
        if (this.minimizable) {
            this.minimizedIcon = this.container.createChild({
                tag: 'div'
            });

            if (this.minReLocate)
            {
				this.minimizedIcon.setLeftTop(this.minimizedIconLeft, this.minimizedIconTop);
            }
/*
            this.minimizedIcon.setStyle({
                position: 'absolute',
                width: '85px'
            });
*/
            this.minimizedIcon.button = new Ext.ux.Window.MinimizeButton(this, this.minimizedIcon);
            this.minimizedIcon.dd = new Ext.ux.DDProxy(this.minimizedIcon.id, 'icons'); //new Ext.dd.DDProxy(this.minimizedIcon.id, 'icons');
            this.on('show', this.handleShow);
            //this.on('hide', this.handleHideWindow);
            this.on('destroy', this.handleDestroy);
        }
    },
    /**
     * Fires after the window has been minimized. Listeners will be called with the following arguments:
     * this : Ext.Window
     * @param {Object} window
     */
    minimize: function(/* this : Ext.Window */window){
        this.hide();
        this.minimizedIcon.button.show();
        
    },
    /**
     * Fires after the window has been maximized. Listeners will be called with the following arguments:
     * this : Ext.Window
     * @param {Object} window
     */
    handleShow: function(/* this : Ext.Window */window){
        this.minimizedIcon.button.hide();
    },
    /**
     * handle the destroy of the window by deleting the minimize icon
     * @param {Object} window
     */
    handleDestroy: function(/* this : Ext.Window */window){
        this.minimizedIcon.button.destroy();
		this.minimizedIcon.dd.unreg();
		//Ext.get(this.minimizedIcon.dd.getDragEl()).remove();
        this.minimizedIcon.dd.destroy();
        this.minimizedIcon.removeAllListeners();
        this.minimizedIcon.remove();
    }
});

// register xtype
Ext.reg('uxwindow', Ext.ux.Window);


/**
 * @class Ext.ux.Window.MinimizeButton
 * @extends Ext.Button
 */
Ext.ux.Window.MinimizeButton = function(win, el){
    this.win = win;
    Ext.ux.Window.MinimizeButton.superclass.constructor.call(this, {
        iconCls: win.minimizedIconCls,
//text: Ext.util.Format.ellipsis(win.title, 12),
        text: (win.minTitle)?win.minTitle:win.title,
        renderTo: el,
        hidden: true,
        cls: 'minimize-icon-text-icon',
        /*handler: function(){
            if (win.minimized || win.hidden) {
                win.show();
            }
            else 
                if (win == win.manager.getActive()) {
                    win.minimize();
                }
                else {
                    win.toFront();
                }
        },*/
        width: win.width,
        clickEvent: 'dblclick'
    });
};

Ext.extend(Ext.ux.Window.MinimizeButton, Ext.Button, {
	
	//clickEvent: 'dblclick',
  initComponent : function(){
        Ext.ux.Window.MinimizeButton.superclass.initComponent.call(this);

        this.addEvents("dblclick");  
  },
  onDblClick: function()  {
      if (this.win.minimized || this.win.hidden) {
          this.win.show();
       } else { 
                if (this.win == this.win.manager.getActive()) {
                    this.win.minimize();
                }
                else {
                    this.win.toFront();
                }
        }
  },
	onRender: function(){
        Ext.ux.Window.MinimizeButton.superclass.onRender.apply(this, arguments);
        this.el.on("dblclick", this.onDblClick,  this);
        this.el.on("click", this.onDblClick,  this);
        
		if (this.win.closable)
		{
			this.cmenu = new Ext.menu.Menu({
				items: [{
					text: 'Show',
					handler: function(){
					this.win.show();
					},
					scope: this
				}, '-', {
					text: 'Close',
					handler: this.closeWin.createDelegate(this, this.win, true),
					scope: this.win
				}]
			});
		}
		else
		{
			this.cmenu = new Ext.menu.Menu({
				items: [{
					text: 'Show',
					handler: function(){
					this.win.show();
					},
					scope: this
				}]
			});
		}
        
        this.el.on('contextmenu', function(e){
            e.stopEvent();
            if (!this.cmenu.el) {
                this.cmenu.render();
            }
            var xy = e.getXY();
            xy[1] -= this.cmenu.el.getHeight();
            this.cmenu.showAt(xy);
        }, this);
    },
    closeWin: function(cMenu, e, win){
        if (!win.isVisible()) {
            win.show();
        }
        else {
            win.restore();
        }
        if (win.closeAction=='hide')
        {
		    win.hide();
		}
		else
		{
			wind.close();
		}
    }
});

/**
 *@class Ext.ux.DDProxy
 * @extends Ext.dd.DDProxy
 */
Ext.ux.DDProxy = function(id, sGroup, config) {
	Ext.ux.DDProxy.superclass.constructor.call(this, id, sGroup, config);
};

Ext.extend(Ext.ux.DDProxy, Ext.dd.DDProxy, {
//Ext.override(Ext.dd.DDProxy, {
    startDrag: function(x, y){
        var dragEl = Ext.get(this.getDragEl());
        var el = Ext.get(this.getEl());
        
        dragEl.applyStyles({
            border: '',
            'z-index': 2000
        });
        //console.log(el.dom.innerHTML);
        var contents = Ext.ux.cloneInnerHTML(el.dom.innerHTML);
        //console.log(contents);
		    dragEl.removeClass(dragEl.dom.getAttribute('class'))
        dragEl.update(contents);
        dragEl.addClass(el.dom.className);
        dragEl.addClass('dd-proxy');
    }
});

/**
 * Helper to clone the innerHTML provided and replace all the element
 * ids with fresh ones
 * @param {Object} value
 */
Ext.ux.cloneInnerHTML = function(value)	{
	if (value) {
		var PLACE_HOLDER = '_*_*_';
		var regex1 = /id="[^"]*"/;
		var regex2 = /_\*_\*_/;
		var idAttribute;
		while(value.match(regex1))	{
			value = value.replace(regex1, PLACE_HOLDER);
		}
		while(value.match(regex2))	{
			value = value.replace(regex2, 'id="' + Ext.id() + '"');
		}
	}
    return value;
};
