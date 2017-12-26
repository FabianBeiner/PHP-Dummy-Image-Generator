# Dynamic Dummy Image Generator<br>— as seen on DummyImage.com

**This script enables you to create placeholder images in a breeze.**

## Usage

Just link to the `image.php` file via the HTML `<img>` element. For example, `<img src ="image.php?size=500x250&type=png&bg=f00&color=ffffff&text=Dummy" alt="Dummy Image">` would create a PNG image with red (#f00) background, white (#ffffff) text, the word “Dummy” written on it and a size of 500px width, 250px height.

This script handles the following parameters, where basically all of them are optional.

* `size` *(default: 640x480) — Examples:<br>500x250 (= 500px width, 250px height)<br>500 (= 500px square) *
* `type` *(default: png)* — Examples:<br>png (= PNG image)<br>gif (= GIF image)<br>jpeg or jpg (= JPEG image)
* `bg` *(default: CC0099)* — Examples:<br>f00 (= #FF0000 as background color)<br>FF0855 (= #FF0855 as background color)
* `color` *(default: FFFFFF)* — Examples:<br>000 (= #000000 as font color)<br>FFFFFF (= #FFFFFF as font color)
* `text` *(default: {WidthOfTheImage}×{HightOfTheImage})* — Examples:<br>Lore Ipsum (= Image has Lore Ipsum written on it)

## License

Please see the [license file](https://github.com/FabianBeiner/PHP-Dummy-Image-Generator/blob/master/LICENSE) for more information.

### Credits
Original idea by [Russel Heimlich](https://github.com/kingkool68/). When I first published this script, [DummyImage.com](https://dummyimage.com/) was not Open Source, so I had to write a small script to replace the function on my own server.
