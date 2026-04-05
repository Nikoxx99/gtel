(function ($) {

	'use strict';

	var SiPremiumTempsData = window.SiPremiumTempsData || {},
		SiPremiumEditor,
		SiPremiumEditorViews,
		SiPremiumControlsViews,
		SiPremiumModules;

	SiPremiumEditorViews = {

		ModalLayoutView: null,
		ModalHeaderView: null,
		ModalHeaderInsertButton: null,
		ModalLoadingView: null,
		ModalBodyView: null,
		ModalErrorView: null,
		LibraryCollection: null,
		KeywordsModel: null,
		ModalCollectionView: null,
		ModalTabsCollection: null,
		ModalTabsCollectionView: null,
		FiltersCollectionView: null,
		FiltersItemView: null,
		ModalTabsItemView: null,
		ModalTemplateItemView: null,
		ModalInsertTemplateBehavior: null,
		ModalTemplateModel: null,
		CategoriesCollection: null,
		ModalPreviewView: null,
		ModalHeaderBack: null,
		ModalHeaderLogo: null,
		KeywordsView: null,
		TabModel: null,
		CategoryModel: null,

		init: function () {
			var self = this;

			self.ModalTemplateModel = Backbone.Model.extend({
				defaults: {
					template_id: 0,
					name: '',
					title: '',
					thumbnail: '',
					preview: '',
					source: '',
					categories: [],
					keywords: []
				}
			});

			self.ModalHeaderView = Marionette.LayoutView.extend({

				id: 'si-premium-template-modal-header',
				template: '#tmpl-si-premium-template-modal-header',

				ui: {
					closeModal: '#si-premium-template-modal-header-close-modal'
				},

				events: {
					'click @ui.closeModal': 'onCloseModalClick'
				},

				regions: {
					headerLogo: '#si-premium-template-modal-header-logo-area',
					headerTabs: '#si-premium-template-modal-header-tabs',
					headerActions: '#si-premium-template-modal-header-actions'
				},

				onCloseModalClick: function () {
					SiPremiumEditor.closeModal();
				}

			});

			self.TabModel = Backbone.Model.extend({
				defaults: {
					slug: '',
					title: ''
				}
			});

			self.LibraryCollection = Backbone.Collection.extend({
				model: self.ModalTemplateModel
			});

			self.ModalTabsCollection = Backbone.Collection.extend({
				model: self.TabModel
			});

			self.CategoryModel = Backbone.Model.extend({
				defaults: {
					slug: '',
					title: ''
				}
			});

			self.KeywordsModel = Backbone.Model.extend({
				defaults: {
					keywords: {}
				}
			});

			self.CategoriesCollection = Backbone.Collection.extend({
				model: self.CategoryModel
			});

			self.KeywordsView = Marionette.ItemView.extend({
				id: 'elementor-template-library-filter',
				template: '#tmpl-si-premium-template-modal-keywords',
				ui: {
					keywords: '.si-premium-library-keywords'
				},

				events: {
					'change @ui.keywords': 'onSelectKeyword'
				},

				onSelectKeyword: function (event) {
					var selected = event.currentTarget.selectedOptions[0].value;
					SiPremiumEditor.setFilter('keyword', selected);
				},

				onRender: function () {
					var $filters = this.$('.si-premium-library-keywords');
					$filters.select2({
						placeholder: 'Choose Widget',
						allowClear: true,
						width: 250
					});
				}
			});

			self.ModalPreviewView = Marionette.ItemView.extend({

				template: '#tmpl-si-premium-template-modal-preview',

				id: 'si-premium-templatate-item-preview-wrap',

				ui: {
					iframe: 'iframe',
					notice: '.si-premium-template-item-notice'
				},


				onRender: function () {

					if (null !== this.getOption('notice')) {
						if (this.getOption('notice').length) {
							var message = "";
							if (-1 !== this.getOption('notice').indexOf("facebook")) {
								message += "<p>Please login with your Facebook account in order to get your Facebook Reviews.</p>";
							} else if (-1 !== this.getOption('notice').indexOf("google")) {
								message += "<p>You need to add your Google API key from Dashboard -> Premium Add-ons for Elementor -> Google Maps</p>";
							} else if (-1 !== this.getOption('notice').indexOf("form")) {
								message += "<p>You need to have <a href='https://wordpress.org/plugins/contact-form-7/' target='_blank'>Contact Form 7 plugin</a> installed and active.</p>";
							}

							this.ui.notice.html('<div><p><strong>Important!</strong></p>' + message + '</div>');
						}
					}

					this.ui.iframe.attr('src', this.getOption('url'));

				}
			});

			self.ModalHeaderBack = Marionette.ItemView.extend({

				template: '#tmpl-si-premium-template-modal-header-back',

				id: 'si-premium-template-modal-header-back',

				ui: {
					button: 'button'
				},

				events: {
					'click @ui.button': 'onBackClick',
				},

				onBackClick: function () {
					SiPremiumEditor.setPreview('back');
				}

			});

			self.ModalHeaderLogo = Marionette.ItemView.extend({

				template: '#tmpl-si-premium-template-modal-header-logo',

				id: 'si-premium-template-modal-header-logo'

			});

			self.ModalBodyView = Marionette.LayoutView.extend({

				id: 'si-premium-template-library-content',

				className: function () {
					return 'library-tab-' + SiPremiumEditor.getTab();
				},

				template: '#tmpl-si-premium-template-modal-content',

				regions: {
					contentTemplates: '.si-premium-templates-list',
					contentFilters: '.si-premium-filters-list',
					contentKeywords: '.si-premium-keywords-list'
				}

			});

			self.ModalInsertTemplateBehavior = Marionette.Behavior.extend({
				ui: {
					insertButton: '.si-premium-template-insert'
				},

				events: {
					'click @ui.insertButton': 'onInsertButtonClick'
				},

				onInsertButtonClick: function () {

					var templateModel = this.view.model,
						innerTemplates = templateModel.attributes.dependencies,
						isPro = templateModel.attributes.pro,
						innerTemplatesLength = Object.keys(innerTemplates).length,
						options = {};

					SiPremiumEditor.layout.showLoadingView();
					if (innerTemplatesLength > 0) {
						for (var key in innerTemplates) {
							$.ajax({
								url: ajaxurl,
								type: 'post',
								dataType: 'json',
								data: {
									action: 'si_premium_inner_template',
									template: innerTemplates[key],
									tab: SiPremiumEditor.getTab()
								}
							});
						}
					}

					if ("valid" === SiPremiumTempsData.license.status || !isPro) {

						elementor.templates.requestTemplateContent(
							templateModel.get('source'),
							templateModel.get('template_id'), {
							data: {
								tab: SiPremiumEditor.getTab(),
								page_settings: false
							},
							success: function (data) {

								if (!data.license) {
									SiPremiumEditor.layout.showLicenseError();
									return;
								}

								console.log("%c Template Inserted Successfully!!", "color: #7a7a7a; background-color: #eee;");

								SiPremiumEditor.closeModal();

								elementor.channels.data.trigger('template:before:insert', templateModel);

								if (null !== SiPremiumEditor.atIndex) {
									options.at = SiPremiumEditor.atIndex;
								}

								elementor.previewView.addChildModel(data.content, options);

								elementor.channels.data.trigger('template:after:insert', templateModel);
								jQuery("#elementor-panel-saver-button-save-options, #elementor-panel-saver-button-publish").removeClass("elementor-disabled");
								SiPremiumEditor.atIndex = null;

							},
							error: function (err) {
								console.log(err);
							}
						}
						);
					} else {
						SiPremiumEditor.layout.showLicenseError();
					}
				}
			});

			self.ModalHeaderInsertButton = Marionette.ItemView.extend({

				template: '#tmpl-si-premium-template-modal-insert-button',

				id: 'si-premium-template-modal-insert-button',

				behaviors: {
					insertTemplate: {
						behaviorClass: self.ModalInsertTemplateBehavior
					}
				}

			});

			self.FiltersItemView = Marionette.ItemView.extend({

				template: '#tmpl-si-premium-template-modal-filters-item',

				className: function () {
					return 'si-premium-template-filter-item';
				},

				ui: function () {
					return {
						filterLabels: '.si-premium-template-filter-label'
					};
				},

				events: function () {
					return {
						'click @ui.filterLabels': 'onFilterClick'
					};
				},

				onFilterClick: function (event) {

					var $clickedInput = jQuery(event.target);
					jQuery('.si-premium-library-keywords').val('');
					SiPremiumEditor.setFilter('category', $clickedInput.val());
					SiPremiumEditor.setFilter('keyword', '');
				}

			});

			self.ModalTabsItemView = Marionette.ItemView.extend({

				template: '#tmpl-si-premium-template-modal-tabs-item',

				className: function () {
					return 'elementor-template-library-menu-item';
				},

				ui: function () {
					return {
						tabsLabels: 'label',
						tabsInput: 'input'
					};
				},

				events: function () {
					return {
						'click @ui.tabsLabels': 'onTabClick'
					};
				},

				onRender: function () {
					if (this.model.get('slug') === SiPremiumEditor.getTab()) {
						this.ui.tabsInput.attr('checked', 'checked');
					}
				},

				onTabClick: function (event) {

					var $clickedInput = jQuery(event.target);
					SiPremiumEditor.setTab($clickedInput.val());
					SiPremiumEditor.setFilter('keyword', '');
				}

			});

			self.FiltersCollectionView = Marionette.CompositeView.extend({

				id: 'si-premium-template-library-filters',

				template: '#tmpl-si-premium-template-modal-filters',

				childViewContainer: '#si-premium-modal-filters-container',

				getChildView: function (childModel) {
					return self.FiltersItemView;
				}

			});

			self.ModalTabsCollectionView = Marionette.CompositeView.extend({

				template: '#tmpl-si-premium-template-modal-tabs',

				childViewContainer: '#si-premium-modal-tabs-items',

				initialize: function () {
					this.listenTo(SiPremiumEditor.channels.layout, 'tamplate:cloned', this._renderChildren);
				},

				getChildView: function (childModel) {
					return self.ModalTabsItemView;
				}

			});

			self.ModalTemplateItemView = Marionette.ItemView.extend({

				template: '#tmpl-si-premium-template-modal-item',

				className: function () {

					var urlClass = ' premium-template-has-url',
						sourceClass = ' elementor-template-library-template-',
						proTemplate = '';

					if ('' === this.model.get('preview')) {
						urlClass = ' premium-template-no-url';
					}

					sourceClass += 'remote';

					if (this.model.get('pro')) {
						proTemplate = ' premium-template-pro';
					}

					return 'elementor-template-library-template' + sourceClass + urlClass + proTemplate;
				},

				ui: function () {
					return {
						previewButton: '.elementor-template-library-template-preview',
					};
				},

				events: function () {
					return {
						'click @ui.previewButton': 'onPreviewButtonClick',
					};
				},

				onPreviewButtonClick: function () {

					if ('' === this.model.get('url')) {
						return;
					}

					SiPremiumEditor.setPreview(this.model);
				},

				behaviors: {
					insertTemplate: {
						behaviorClass: self.ModalInsertTemplateBehavior
					}
				}
			});

			self.ModalCollectionView = Marionette.CompositeView.extend({

				template: '#tmpl-si-premium-template-modal-templates',

				id: 'si-premium-template-library-templates',

				childViewContainer: '#si-premium-modal-templates-container',

				initialize: function () {

					this.listenTo(SiPremiumEditor.channels.templates, 'filter:change', this._renderChildren);
				},

				filter: function (childModel) {

					var filter = SiPremiumEditor.getFilter('category'),
						keyword = SiPremiumEditor.getFilter('keyword');

					if (!filter && !keyword) {
						return true;
					}

					if (keyword && !filter) {
						return _.contains(childModel.get('keywords'), keyword);
					}

					if (filter && !keyword) {
						return _.contains(childModel.get('categories'), filter);
					}

					return _.contains(childModel.get('categories'), filter) && _.contains(childModel.get('keywords'), keyword);

				},

				getChildView: function (childModel) {
					return self.ModalTemplateItemView;
				},

				onRenderCollection: function () {

					var container = this.$childViewContainer,
						items = this.$childViewContainer.children(),
						tab = SiPremiumEditor.getTab();

					if ('section' === tab || 'page' === tab || 'local' === tab) {
						return;
					}

					// Wait for thumbnails to be loaded
					container.imagesLoaded(function () { }).done(function () {
						self.masonry.init({
							container: container,
							items: items
						});
					});
				}

			});

			self.ModalLayoutView = Marionette.LayoutView.extend({

				el: '#si-premium-template-modal',

				regions: SiPremiumTempsData.modalRegions,

				initialize: function () {

					this.getRegion('modalHeader').show(new self.ModalHeaderView());
					this.listenTo(SiPremiumEditor.channels.tabs, 'filter:change', this.switchTabs);
					this.listenTo(SiPremiumEditor.channels.layout, 'preview:change', this.switchPreview);

				},

				switchTabs: function () {
					this.showLoadingView();
					SiPremiumEditor.setFilter('keyword', '');
					SiPremiumEditor.requestTemplates(SiPremiumEditor.getTab());
				},

				switchPreview: function () {

					var header = this.getHeaderView(),
						preview = SiPremiumEditor.getPreview();

					var filter = SiPremiumEditor.getFilter('category'),
						keyword = SiPremiumEditor.getFilter('keyword');

					if ('back' === preview) {
						header.headerLogo.show(new self.ModalHeaderLogo());
						header.headerTabs.show(new self.ModalTabsCollectionView({
							collection: SiPremiumEditor.collections.tabs
						}));

						header.headerActions.empty();
						SiPremiumEditor.setTab(SiPremiumEditor.getTab());

						if ('' != filter) {
							SiPremiumEditor.setFilter('category', filter);
							jQuery('#si-premium-modal-filters-container').find("input[value='" + filter + "']").prop('checked', true);

						}

						if ('' != keyword) {
							SiPremiumEditor.setFilter('keyword', keyword);
						}

						return;
					}

					if ('initial' === preview) {
						header.headerActions.empty();
						header.headerLogo.show(new self.ModalHeaderLogo());
						return;
					}

					this.getRegion('modalContent').show(new self.ModalPreviewView({
						'preview': preview.get('preview'),
						'url': preview.get('url'),
						'notice': preview.get('notice')
					}));

					header.headerLogo.empty();
					header.headerTabs.show(new self.ModalHeaderBack());
					header.headerActions.show(new self.ModalHeaderInsertButton({
						model: preview
					}));

				},

				getHeaderView: function () {
					return this.getRegion('modalHeader').currentView;
				},

				getContentView: function () {
					return this.getRegion('modalContent').currentView;
				},

				showLoadingView: function () {
					this.modalContent.show(new self.ModalLoadingView());
				},

				showLicenseError: function () {
					this.modalContent.show(new self.ModalErrorView());
				},

				showTemplatesView: function (templatesCollection, categoriesCollection, keywords) {

					this.getRegion('modalContent').show(new self.ModalBodyView());

					var contentView = this.getContentView(),
						header = this.getHeaderView(),
						keywordsModel = new self.KeywordsModel({
							keywords: keywords
						});

					SiPremiumEditor.collections.tabs = new self.ModalTabsCollection(SiPremiumEditor.getTabs());

					header.headerTabs.show(new self.ModalTabsCollectionView({
						collection: SiPremiumEditor.collections.tabs
					}));

					contentView.contentTemplates.show(new self.ModalCollectionView({
						collection: templatesCollection
					}));

					contentView.contentFilters.show(new self.FiltersCollectionView({
						collection: categoriesCollection
					}));

					contentView.contentKeywords.show(new self.KeywordsView({
						model: keywordsModel
					}));

				}

			});

			self.ModalLoadingView = Marionette.ItemView.extend({
				id: 'si-premium-template-modal-loading',
				template: '#tmpl-si-premium-template-modal-loading'
			});

			self.ModalErrorView = Marionette.ItemView.extend({
				id: 'si-premium-template-modal-loading',
				template: '#tmpl-si-premium-template-modal-error'
			});

		},

		masonry: {

			self: {},
			elements: {},

			init: function (settings) {

				var self = this;
				self.settings = $.extend(self.getDefaultSettings(), settings);
				self.elements = self.getDefaultElements();

				self.run();
			},

			getSettings: function (key) {
				if (key) {
					return this.settings[key];
				} else {
					return this.settings;
				}
			},

			getDefaultSettings: function () {
				return {
					container: null,
					items: null,
					columnsCount: 3,
					verticalSpaceBetween: 30
				};
			},

			getDefaultElements: function () {
				return {
					$container: jQuery(this.getSettings('container')),
					$items: jQuery(this.getSettings('items'))
				};
			},

			run: function () {
				var heights = [],
					distanceFromTop = this.elements.$container.position().top,
					settings = this.getSettings(),
					columnsCount = settings.columnsCount;

				distanceFromTop += parseInt(this.elements.$container.css('margin-top'), 10);

				this.elements.$container.height('');

				this.elements.$items.each(function (index) {
					var row = Math.floor(index / columnsCount),
						indexAtRow = index % columnsCount,
						$item = jQuery(this),
						itemPosition = $item.position(),
						itemHeight = $item[0].getBoundingClientRect().height + settings.verticalSpaceBetween;

					if (row) {
						var pullHeight = itemPosition.top - distanceFromTop - heights[indexAtRow];
						pullHeight -= parseInt($item.css('margin-top'), 10);
						pullHeight *= -1;
						$item.css('margin-top', pullHeight + 'px');
						heights[indexAtRow] += itemHeight;
					} else {
						heights.push(itemHeight);
					}
				});

				this.elements.$container.height(Math.max.apply(Math, heights));
			}
		}

	};

	SiPremiumControlsViews = {

		PremiumSearchView: null,

		init: function () {

			var self = this;

			self.PremiumSearchView = window.elementor.modules.controls.BaseData.extend({

				onReady: function () {

					var action = this.model.attributes.action,
						queryParams = this.model.attributes.query_params;

					this.ui.select.find('option').each(function (index, el) {
						$(this).attr('selected', true);
					});

					this.ui.select.select2({
						ajax: {
							url: function () {

								var query = '';

								if (queryParams.length > 0) {
									$.each(queryParams, function (index, param) {

										if (window.elementor.settings.page.model.attributes[param]) {
											query += '&' + param + '=' + window.elementor.settings.page.model.attributes[param];
										}
									});
								}

								return ajaxurl + '?action=' + action + query;
							},
							dataType: 'json'
						},
						placeholder: 'Please enter 3 or more characters',
						minimumInputLength: 3
					});

				},

				onBeforeDestroy: function () {

					if (this.ui.select.data('select2')) {
						this.ui.select.select2('destroy');
					}

					this.$el.remove();
				}

			});

			window.elementor.addControlView('premium_search', self.PremiumSearchView);

		}

	};


	SiPremiumModules = {

		getDataToSave: function (data) {
			data.id = window.elementor.config.post_id;
			return data;
		},

		init: function () {
			if (window.elementor.settings.premium_template) {
				window.elementor.settings.premium_template.getDataToSave = this.getDataToSave;
			}

			if (window.elementor.settings.premium_page) {
				window.elementor.settings.premium_page.getDataToSave = this.getDataToSave;
				window.elementor.settings.premium_page.changeCallbacks = {
					custom_header: function () {
						this.save(function () {
							elementor.reloadPreview();

							elementor.once('preview:loaded', function () {
								elementor.getPanelView().setPage('premium_page_settings');
							});
						});
					},
					custom_footer: function () {
						this.save(function () {
							elementor.reloadPreview();

							elementor.once('preview:loaded', function () {
								elementor.getPanelView().setPage('premium_page_settings');
							});
						});
					}
				};
			}

		}

	};

	SiPremiumEditor = {

		modal: false,
		layout: false,
		collections: {},
		tabs: {},
		defaultTab: '',
		channels: {},
		atIndex: null,

		init: function () {

			window.elementor.on(
				'document:loaded',
				window._.bind(SiPremiumEditor.onPreviewLoaded, SiPremiumEditor)
			);

			SiPremiumEditorViews.init();
			SiPremiumControlsViews.init();
			SiPremiumModules.init();

		},

		onPreviewLoaded: function () {

			this.initPremTempsButton();

			window.elementor.$previewContents.on(
				'click.addPremiumTemplate',
				'.si-premium-template-add-section-btn',
				_.bind(this.showTemplatesModal, this)
			);

			this.channels = {
				templates: Backbone.Radio.channel('PREMIUM_EDITOR:templates'),
				tabs: Backbone.Radio.channel('PREMIUM_EDITOR:tabs'),
				layout: Backbone.Radio.channel('PREMIUM_EDITOR:layout'),
			};

			this.tabs = SiPremiumTempsData.tabs;
			this.defaultTab = SiPremiumTempsData.defaultTab;

		},

		initPremTempsButton: function () {

			var $addNewSection = window.elementor.$previewContents.find('.elementor-add-new-section'),
				addPremiumTemplate = "<div class='elementor-add-section-area-button bg-gradient-primary si-premium-template-add-section-btn' title='Add Premium Template'>Si.</div>",
				$addPremiumTemplate;

			if ($addNewSection.length && SiPremiumTempsData.PremiumTemplatesBtn) {
				$addPremiumTemplate = $(addPremiumTemplate).prependTo($addNewSection);
			}

			window.elementor.$previewContents.on(
				'click.addPremiumTemplate',
				'.elementor-editor-section-settings .elementor-editor-element-add',
				function () {

					var $this = $(this),
						$section = $this.closest('.elementor-top-section'),
						modelID = $section.data('model-cid');

					if (elementor.previewView.collection.length) {
						$.each(elementor.previewView.collection.models, function (index, model) {
							if (modelID === model.cid) {
								SiPremiumEditor.atIndex = index;
							}
						});
					}


					if (SiPremiumTempsData.PremiumTemplatesBtn) {
						setTimeout(function () {
							var $addNew = $section.prev('.elementor-add-section').find('.elementor-add-new-section');
							$addNew.prepend(addPremiumTemplate);
						}, 100);
					}

				}
			);

		},

		getFilter: function (name) {

			return this.channels.templates.request('filter:' + name);
		},

		setFilter: function (name, value) {
			this.channels.templates.reply('filter:' + name, value);
			this.channels.templates.trigger('filter:change');
		},

		getTab: function () {
			return this.channels.tabs.request('filter:tabs');
		},

		setTab: function (value, silent) {

			this.channels.tabs.reply('filter:tabs', value);

			if (!silent) {
				this.channels.tabs.trigger('filter:change');
			}

		},

		getTabs: function () {

			var tabs = [];

			_.each(this.tabs, function (item, slug) {
				tabs.push({
					slug: slug,
					title: item.title
				});
			});

			return tabs;
		},

		getPreview: function (name) {
			return this.channels.layout.request('preview');
		},

		setPreview: function (value, silent) {

			this.channels.layout.reply('preview', value);

			if (!silent) {
				this.channels.layout.trigger('preview:change');
			}
		},

		getKeywords: function () {

			var keywords = [];

			_.each(this.keywords, function (title, slug) {
				tabs.push({
					slug: slug,
					title: title
				});
			});

			return keywords;
		},

		showTemplatesModal: function () {

			this.getModal().show();

			if (!this.layout) {
				this.layout = new SiPremiumEditorViews.ModalLayoutView();
				this.layout.showLoadingView();
			}

			this.setTab(this.defaultTab, true);
			this.requestTemplates(this.defaultTab);
			this.setPreview('initial');

		},

		requestTemplates: function (tabName) {

			var self = this,
				tab = self.tabs[tabName];

			self.setFilter('category', false);

			if (tab && tab.data && tab.data.templates && tab.data.categories.templates && tab.data.categories) {
				self.layout.showTemplatesView(tab.data.templates, tab.data.categories, tab.data.keywords);
			} else {
				$.ajax({
					url: ajaxurl,
					type: 'get',
					dataType: 'json',
					data: {
						action: 'si_premium_get_templates',
						tab: tabName
					},
					success: function (response) {
						console.log("%cTemplates Retrieved Successfully!!", "color: #7a7a7a; background-color: #eee;");

						var templates = new SiPremiumEditorViews.LibraryCollection(response.data.templates),
							categories = new SiPremiumEditorViews.CategoriesCollection(response.data.categories);

						if( self.tabs[tabName] ) {
							self.tabs[tabName].data = {
								templates: templates,
								categories: categories,
								keywords: response.data.keywords
							};
						}

						self.layout.showTemplatesView(templates, categories, response.data.keywords);

					}
				});
			}

		},

		closeModal: function () {
			this.getModal().hide();
		},

		getModal: function () {

			if (!this.modal) {
				this.modal = elementor.dialogsManager.createWidget('lightbox', {
					id: 'si-premium-template-modal',
					className: 'elementor-templates-modal',
					closeButton: false
				});
			}

			return this.modal;

		}

	};

	$(window).on('elementor:init', SiPremiumEditor.init);

})(jQuery);