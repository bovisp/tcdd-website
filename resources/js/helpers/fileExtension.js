export default function fileExtension (filename) {
    let extension = filename.split('.').pop()

    switch (extension) {
        case 'gif':
        case 'png':
        case 'jpg':
            return 'image'
        case 'mp4':
            return 'video'
    }
}