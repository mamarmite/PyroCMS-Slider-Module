PyroCMS-Slider-Module
=====================

1. Add multiple sliders to your PyroCMS site using this simple streams-based module.
2. Order your slides and add content to them: title, description, link.
3. Added the functionality to duplicate a slider, getting all its slides with it.
4. Assign new fields to Slider and Slides streams.
5. Sync slider from already uploaded files in a folder (need the Samul Goodwin's files_folders field type)
6. Assign slider to another stream with the Slider Field type base on (or more inspired) the Samul Goodwin's files_folders field type.

## Installation
* Install the [File Folder](https://github.com/sgoodwin10/PyroStreams-File-Folders) from @sgoodwin10 (see [pyrocms doc](http://docs.pyrocms.com/2.2/manual/guides/addons/field_types) to know how to install a field type)
* Move the slider module to your prefered addons path
* Install the module from pyrocms's admin panel.

## Updates from 1.0.0
### Warnings
* This is kind of lazy, update from 1.0.0 will not save your current slider and slides data. Fresh install tested only. Sorry for that.

# Plugin
##### Default slider id is 1.
```
{{ slider:images }}
<div>
    <h1><a href="{{ slide_link }}" title="{{ slide_title }}">{{ slide_title }}</a></h1>
    <p>{{ slide_desc }}</p>
    <a href="{{ slide_image:path }}" title="{{ slide_image:alt }}">
    	<img src="{{url:site}}files/large/{{ slide_image:id }}" alt="{{ slide_image:alt }}" />
    </a>
</div>
{{ /slider:images }}
```

### Other attributes 
* `id` 		`Integer`
* `slug` 	`String`
* `where` 	`String`
* `ranom` 	`Boolean`
* `limit` 	`Integer`

### Simple Exemple
``` 
{{ slider:images slug=slider_slug where="A string of your where (only applied to slides SQL)" random=true limit=5 }}
	Your awesome view.
{{ /slider:images }}
```

# Todo
##### [<i>n</i>] are priorities.
- [ ] Add filters to all slides view [2]
- [ ] Put more love to the slides view. [3]
- [x] Add a slider field types to be able to link slider to pages or other streams. [2]
- [x] Sync slider with an already uploaded files in a folder. [2]
- [ ] Sync slider with an already uploaded files in a folder from a buton in the slider view or slide view? [3] 
- [ ] Shortcut to add slide into a slider. [3]
- [ ] Shortcut to add slide and then assign it to a slider. [3]
- [ ] Make slide view. [?]
- [ ] Make slider view. [?]
- [ ] Add settings section with js slider "drivers": begin with Open Source Sliders: Owl, Flexslider, Nivo, etc. [?]

# Versions
* 1.0.1 - Added a new field to the slider stream to impletement the sync with folder feature
* 1.0.2 - 1.0.3 : Set the new field to not required.
* 1.0.4
	* Field type Slider added to the branch (but need to be install in the field_types folder of you shared_addons)
	* Fixed cache problem between admin and plugin.
	* Fixed Draft / Live function of slides (broke by the changes of field names).