/**
 * Direct-to-DigitalOcean-Spaces upload via a presigned PUT URL.
 * Backend: GET /upload/presigned-url?name=... -> { url, path, publicUrl }
 */

export interface UploadResult {
    path: string
    publicUrl: string
}

function randomString(length: number): string {
    const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789'
    let result = ''
    for (let i = 0; i < length; i++) {
        result += chars.charAt(Math.floor(Math.random() * chars.length))
    }
    return result
}

function putToSpaces(
    url: string,
    file: File,
    onProgress?: (percent: number) => void,
): Promise<boolean> {
    return new Promise((resolve) => {
        const xhr = new XMLHttpRequest()

        xhr.upload.addEventListener('progress', (event) => {
            if (event.lengthComputable && onProgress) {
                onProgress(Math.round((event.loaded / event.total) * 100))
            }
        })

        xhr.onloadend = () => resolve(xhr.status === 200)

        xhr.open('PUT', url, true)
        xhr.setRequestHeader('Content-Type', 'application/octet-stream')
        // Must match the ACL signed into the presigned URL.
        xhr.setRequestHeader('x-amz-acl', 'public-read')
        xhr.send(file)
    })
}

/**
 * Upload a single file to Spaces and resolve with its stored path + public URL.
 * Throws on failure.
 */
export async function uploadToSpaces(
    file: File,
    onProgress?: (percent: number) => void,
): Promise<UploadResult> {
    const ext = file.name.split('.').pop()?.toLowerCase() ?? 'bin'
    const filename = `${randomString(32)}.${ext}`

    const csrfToken = document
        .querySelector('meta[name="csrf-token"]')
        ?.getAttribute('content')

    const response = await fetch(
        `/upload/presigned-url?name=${encodeURIComponent(filename)}`,
        {
            headers: {
                Accept: 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                ...(csrfToken ? { 'X-CSRF-TOKEN': csrfToken } : {}),
            },
        },
    )

    if (!response.ok) {
        throw new Error('Failed to get upload URL')
    }

    const data = (await response.json()) as {
        url: string
        path: string
        publicUrl: string
    }

    const uploaded = await putToSpaces(data.url, file, onProgress)
    if (!uploaded) {
        throw new Error('Upload failed')
    }

    return { path: data.path, publicUrl: data.publicUrl }
}
