# PyroStreams Slider Field Type
Allows you to assign a slider to a stream entry to use for your js slider.

## Installation
To install, download from GitHub and rename the folder to "slider". Put this in your addons/site_ref/field_types/ or addons/shared_addons/field_types folder. Once you've placed it into one of these folders, it'll be ready to use with PyroStreams.

## Usage
Will allow for a loop that will show each file
To display the files inside the folder assigned to the entry, you can run them in a cycle just like you would the main stream with the related function:

        {{ streams:cycle stream="your_stream" }}

	        {{ slider_field:id }}
	        {{ slider_field:slider_slug }}
	        {{ slider_field:slider_name }}

        {{ /streams:cycle }}

And then you can use the slider module's plugin:
```
{{ slider:images id=slider_field:id }}
<div>
    <h1><a href="{{ slide_link }}" title="{{ slide_title }}">{{ slide_title }}</a></h1>
    <p>{{ slide_desc }}</p>
    <a href="{{ slide_image:path }}" title="">
    	<img src="{{url:site}}files/large/{{ slide_image:id }}" alt="{{ slide_image:alt }}" />
    </a>
</div>
{{ /slider:images }}
```
