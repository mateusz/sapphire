/**
 * Linking handlers for HtmlEditorField sidebar
 */
LinkForm.prototype.handlers = {
	Internal: {
		/**
		 * Priority is here to obtain desired processing order, which may be important.
		 * The display order is controlled via HtmlEditorConfig::addLinkOption.
		 */
		priority: 10,

		/**
		 * "Inserting" handlers translate the linking form values into <a> tag props. Used when the 
		 * user clicks "insert link". Only one specific handler will be called.
		 *
		 * @param values Form field values to be used when building the link
		 * @return Data structure with <a> tag elements
		 */
		inserting: function(values) {
			var href = '[sitetree_link id=' + values.SiteTreeID + ']';
			if (values.Anchor) href += '#' + values.Anchor;
			return {
				href: href,
				innerHTML: values.LinkText,
				title: values.Description,
				target: values.TargetBlank ? '_blank' : ''
			}
		},
		/**
		 * "Matching" handlers translate the <a> tag props into the linking form values. Invoked when
		 * a new selection is made. All handlers will be called when the user performs a selection
		 * until one is found that gives a result.
		 *
		 * @param linkdata <a> tag properties to be used for matching
		 * @return Data structure to populate the form fields from.
		 */
		matching: function(linkdata) {
			var match;
			if (match = linkdata.href.match(/^\[sitetree_link id=([0-9]+)\](#.*)?$/)) {
				return {
					LinkType: 'Internal',
					SiteTreeID: match[1],
					Anchor: match[2] ? match[2].substr(1) : '',
					LinkText: linkdata.linkText,
					Description: linkdata.title,
					TargetBlank: linkdata.target ? true : false
				}
			}
		}
	},
	Anchor: {
		priority: 20,
		inserting: function(values) {
			return {
				href: (values.Anchor.charAt(0)=='#') ? values.Anchor : '#'+values.Anchor,
				innerHTML: values.LinkText,
				title: values.Description,
				target: values.TargetBlank ? '_blank' : ''
			}
		},
		matching: function(linkdata) {
			var match;
			if (match = linkdata.href.match(/^#(.*)$/)) {
				return {
					LinkType: 'Anchor',
					Anchor: match[1],
					LinkText: linkdata.linkText,
					Description: linkdata.title,
					TargetBlank: linkdata.target ? true : false
				}
			}
		}
	},
	Email: {
		priority: 30,
		inserting: function(values) {
			return {
				href: 'mailto:'+values.Email,
				innerHTML: values.LinkText,
				title: values.Description,
				target: values.TargetBlank ? '_blank' : ''
			}
		},
		matching: function(linkdata) {
			var match;
			if (match = linkdata.href.match(/^mailto:(.*)$/)) {
				return {
					LinkType: 'Email',
					email: match[1],
					LinkText: linkdata.linkText,
					Description: linkdata.title
				} 
			}
		}
	},
	File: {
		priority: 40,
		inserting: function(values) {
			var href = '[asset_link id=' + values.File + ']';
			return {
				href: href,
				innerHTML: values.LinkText,
				title: values.Description,
				target: values.TargetBlank ? '_blank' : ''
			}
		},
		matching: function(linkdata) {
			var match;
			if (match = linkdata.href.match(/^\[asset_link id=([0-9]+)\]$/)) {
				return {
					LinkType: 'File',
					File: match[1],
					LinkText: linkdata.linkText,
					Description: linkdata.title
				}
			}
		}
	},
	// Note: this needs to go last as this "matching" handler is the most relaxed one.
	External: {
		priority: 50,
		inserting: function(values) {
			return {
				href: values.Address,
				innerHTML: values.LinkText,
				title: values.Description,
				target: values.TargetBlank ? '_blank' : ''
			}
		},
		matching: function(linkdata) {
			if (linkdata.href) {
				return {
					LinkType: 'External',
					Address: linkdata.href,
					LinkText: linkdata.linkText,
					Description: linkdata.title,
					TargetBlank: linkdata.target ? true : false
				}
			}
		}
	},
	/**
	 * Catch-all, executed if all else fails.
	 *
	 * @params selection the text that has been selected
	 * @return null or a structure to be used to populate the relevant form (see handlers above)
	 */
	__notMatching: function(selection) {}
}

/**
 * Customise this if you need some special action to be performed when the text selection is changed.
 *
 * @param selectionData Parsed <a> tag properties.
 */
LinkForm.prototype.onSelect = function(selectionData) {
	var form = this;
	if (selectionData.linkText) {
		jQuery('#Form_EditorToolbarLinkForm :input[name$="_LinkText"]').each(function() {
			Form.Element.setValue(form.elements[this.name], selectionData.linkText);
		});
	}
	if (selectionData.title) {
		jQuery('#Form_EditorToolbarLinkForm :input[name$="_Description"]').each(function() {
			Form.Element.setValue(form.elements[this.name], selectionData.title);
		});
	}
}
